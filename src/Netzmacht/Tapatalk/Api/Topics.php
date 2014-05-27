<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api;

use Netzmacht\Tapatalk\Api\Config\Features;
use Netzmacht\Tapatalk\Api\Search\AdvancedSearch;
use Netzmacht\Tapatalk\Api\Search\SearchResult;
use Netzmacht\Tapatalk\Api\Topics\SearchTopicPost;
use Netzmacht\Tapatalk\Api\Topics\SubscribedTopic;
use Netzmacht\Tapatalk\Api\Topics\Topic;
use Netzmacht\Tapatalk\Api\Topics\TopicPost;
use Netzmacht\Tapatalk\Api\Topics\TopicResult;
use Netzmacht\Tapatalk\Api\Topics\TopicPostResult;
use Netzmacht\Tapatalk\Api\Topics\TopicStatus;
use Netzmacht\Tapatalk\Api;
use Netzmacht\Tapatalk\Result;
use Netzmacht\Tapatalk\Transport;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;


/**
 * Class Topics
 * @package Netzmacht\Tapatalk\Api
 */
class Topics extends Api
{
	const UNSUBSCRIBE_ALL    = 'ALL';

	const LIST_STICKY        = 'TOP';
	const LIST_ANNOUNCEMENTS = 'ANN';

	const STATE_PUBLISHED               = 'published';
	const STATE_MOD_PUBLISHING_REQUIRED = 'moderator';


	/**
	 * List all topics of a forum. Mode can be used to get only sticky topics or announcements
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=3#get_topic
	 * @param $forumId
	 * @param int $limit
	 * @param int $offset
	 * @param null $mode
	 * @return Topic[]|TopicResult
	 */
	public function getTopics($forumId, $limit = 50, $offset = 0, $mode = null)
	{
		$this->assertValidListMode($mode);

		$request = $this->transport->createMethodCall('get_topic')
			->set('forum_id', (string)$forumId)
			->set('start_num', $offset)
			->set('last_num', $offset + $limit - 1);

		if($mode) {
			$request->set('mode', $mode);
		}

		$response = $request->call();
		$this->assert()->noResultState($response);

		return TopicResult::fromResponse($response, $offset);
	}


	/**
	 * List all unread topics. The result will be a combination of latest post in topic an the topic itself.
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=3#get_unread_topic
	 * @param int $limit
	 * @param int $offset
	 * @param null $searchId
	 * @param null $filters
	 * @return TopicPostResult|TopicPost[]
	 */
	public function getUnreadTopicPosts($limit = 50, $offset = 0, $searchId = null, $filters = null)
	{
		return $this->queryTopicPosts('get_unread_topic', $limit, $offset, $searchId, $filters);
	}


	/**
	 * List all latest topics. The result will be a combination of latest post in topic an the topic itself.
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=3#get_latest_topic
	 * @param int $limit
	 * @param int $offset
	 * @param null $searchId
	 * @param null $filters
	 * @return TopicPostResult|TopicPost[]
	 */
	public function getLatestTopics($limit = 50, $offset = 0, $searchId = null, $filters = null)
	{
		return $this->queryTopicPosts('get_latest_topic', $limit, $offset, $searchId, $filters);
	}


	/**
	 *
	 * @param array $topicIds
	 * @return Result|TopicStatus[]
	 */
	public function getStatuses(array $topicIds)
	{
		$topicIds = array_map('strval', $topicIds);

		$statuses = array();
		$response = $this->transport->call('get_topic_status', array('topic_id_array' => $topicIds));
		$this->assert()->resultSuccess($response);

		foreach($response->get('status') as $status) {
			$statuses[] = TopicStatus::fromResponse($status);
		}

		return new Result($statuses);
	}


	/**
	 * List all subscribed topics
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=6#get_subscribed_topic
	 * @param int $limit
	 * @param int $offset
	 * @return Result|SubscribedTopic[]
	 */
	public function getSubscribed($limit = 50, $offset = 0)
	{
		$response = $this->transport->call('get_subscribed_topic', array(
			'start_num' => $offset,
			'last_num'  => $limit + $offset - 1
		));

		$this->assert()->noResultState($response);
		$topics = array();

		foreach($response->get('topics') as $topic) {
			$topics[] = SubscribedTopic::fromResponse($topic);
		}

		return new Result($topics, $response->get('total_topic_num'), $offset);
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=6#subscribe_topic
	 * @param $topicId
	 */
	public function subscribeTopic($topicId)
	{
		$response = $this->transport->call('subscribe_topic', array('topic_id' => (string)$topicId));
		$this->assert()->resultSuccess($response);
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=6#unsubscribe_topic
	 * @param $topicId
	 */
	public function unsubscribeTopic($topicId)
	{
		if($topicId == static::UNSUBSCRIBE_ALL) {
			$this->assert()->featureSupported(Features::MASS_SUBSCRIBE);
		}

		$response = $this->transport->call('unsubscribe_topic', array('topic_id' => (string)$topicId));
		$this->assert()->resultSuccess($response);
	}


	/**
	 * Mark given topics as read
	 * @param array $topicIds
	 */
	public function markTopicsAsRead(array $topicIds)
	{
		$topicIds = array_map('strval', $topicIds);

		$response = $this->transport->call('mark_topic_read', array('topic_id_array' => $topicIds));
		$this->assert()->resultSuccess($response);
	}


	/**
	 * Create a new topic. Return an array with topic id and current publishing state.
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=3#new_topic
	 * @param $forumId
	 * @param $subject
	 * @param $body
	 * @param null $prefixId
	 * @param array $attachmentIds
	 * @param null $groupId
	 * @return array
	 */
	public function createNewTopic($forumId, $subject, $body, $prefixId = null, array $attachmentIds = null, $groupId = null)
	{
		$request = $this->transport->createMethodCall('new_topic')
			->set('forum_id', (string)$forumId)
			->set('subject', $subject, true)
			->set('body', $body, true);

		if($prefixId || $attachmentIds) {
			$request->set('prefix_id', (string)$prefixId);
		}

		if($attachmentIds) {
			$request->set('attachment_id_array', array_map('strval', $attachmentIds));
			$request->set('group_id', (string)$groupId);
		}

		$response = $request->call();
		$this->assert()->resultSuccess($response);

		return array(
			'topicId' => $response->get('topic_id'),
			'state'   => $response->get('state' ? static::STATE_MOD_PUBLISHING_REQUIRED : static::STATE_PUBLISHED)
		);
	}


	/**
	 * @param $keywords
	 * @param int $limit
	 * @param int $offset
	 * @param null $searchId
	 * @return Result|TopicPost[]
	 */
	public function search($keywords, $limit = 20, $offset = 0, $searchId = null)
	{
		$request = $this->transport->createMethodCall('search_topic')
			->set('search_string', $keywords, true)
			->set('start_num', $offset)
			->set('last_num', $limit + $offset - 1);

		if($searchId) {
			$request->set('search_id', (string)$searchId);
		}

		$response = $request->call();
		$this->assert()->noResultState($response);

		return $this->createSearchResult($response, $offset, $searchId);
	}


	/**
	 * @param array $filters
	 * @param int $limit
	 * @param int $offset
	 * @param null $searchId
	 * @return SearchResult
	 */
	public function advancedSearch(array $filters, $limit = 20, $offset = 20, $searchId = null)
	{
		$this->assert()->featureSupported(Features::ADVANCED_SEARCH);

		$method = $this->transport->createMethodCall('search', array('filters' => array('showposts' => 0)));
		AdvancedSearch::applyFilters($method, $filters, $limit, $offset, $searchId);

		$response = $method->call();
		$this->assert()->noResultState($response);

		return $this->createSearchResult($response, $offset, $response->get('search_id'));
	}


	/**
	 * @param $method
	 * @param $limit
	 * @param $offset
	 * @param $searchId
	 * @param $filters
	 * @return TopicPostResult
	 */
	private function queryTopicPosts($method, $limit, $offset, $searchId, $filters)
	{
		$params = array(
			'start_num' => $offset,
			'last_num'  => $limit + $offset - 1,
		);

		if($searchId || $filters) {
			$params['search_id'] = (string)$searchId;
		}

		$params   = $this->appendFilters($params, $filters);
		$response = $this->transport->call($method, $params);

		$this->assert()->resultSuccess($response);

		return TopicPostResult::fromResponse($response, $offset);
	}


	/**
	 * @param $mode
	 * @throws \InvalidArgumentException
	 */
	private function assertValidListMode($mode)
	{
		if($mode !== null && $mode != static::LIST_ANNOUNCEMENTS && $mode != static::LIST_STICKY) {
			throw new \InvalidArgumentException('Invalid list topic mode given: ' . $mode);
		}
	}

	/**
	 * @param $name
	 * @throws \InvalidArgumentException
	 */
	private function assertValidFilterName($name)
	{
		if($name != 'only_in' && $name != 'not_in') {
			throw new \InvalidArgumentException('Invalid filter name given: ' . $name);
		}
	}

	/**
	 * @param $params
	 * @param $filters
	 */
	private function appendFilters($params, $filters)
	{
		if(is_array($filters) && !empty($filters)) {
			$compiled = array();
			foreach($filters as $name => $ids) {
				$this->assertValidFilterName($name);
				$compiled[$name] = (array)$ids;
			}

			$params['filters'] = $compiled;
		}

		return $params;
	}


	/**
	 * @param $offset
	 * @param $searchId
	 * @param MethodCallResponse $response
	 * @return SearchResult
	 */
	private function createSearchResult(MethodCallResponse $response, $offset, $searchId)
	{
		$topics = array();

		foreach($response->get('topics') as $topic) {
			$topics[] = SearchTopicPost::fromResponse($topic);
		}

		return new SearchResult($topics, $response->get('total_topic_num'), $offset, $searchId);
	}


}