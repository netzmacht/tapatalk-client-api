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
use Netzmacht\Tapatalk\Api\Posts\PositionedPostResult;
use Netzmacht\Tapatalk\Api\Posts\Post;
use Netzmacht\Tapatalk\Api\Posts\PostFull;
use Netzmacht\Tapatalk\Api\Posts\PostQuote;
use Netzmacht\Tapatalk\Api\Posts\PostResult;
use Netzmacht\Tapatalk\Api;
use Netzmacht\Tapatalk\Api\Posts\RawPost;
use Netzmacht\Tapatalk\Api\Posts\SearchPost;
use Netzmacht\Tapatalk\Api\Search\AdvancedSearch;
use Netzmacht\Tapatalk\Api\Search\SearchResult;


class Posts extends Api
{

	const STATE_PUBLISHED               = 'published';
	const STATE_MOD_PUBLISHING_REQUIRED = 'moderator';

	/**
	 * Get posts of a topic
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=4#get_thread_by
	 * @param $topicId
	 * @param int $limit
	 * @param int $offset
	 * @param bool $asHtml
	 * @return PostResult|PostFull[]
	 */
	public function getPosts($topicId, $limit = 50, $offset = 0, $asHtml = false)
	{
		$params = array(
			'topicId'     => (string)$topicId,
			'start_num'   => $offset,
			'last_num'    => $offset + $limit - 1,
			'return_html' => $asHtml
		);

		$response = $this->transport->call('get_thread', $params);
		$this->assert()->noResultState($response);
		$this->assert()->noResultState($response);

		return PostResult::fromResponse($response);
	}

	/**
	 * Get unread posts of a topic
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=4#get_thread_by_unread
	 * @param $topicId
	 * @param int $limit
	 * @param bool $asHtml
	 * @return PositionedPostResult|PostFull[]
	 */
	public function getUnreadPosts($topicId, $limit = 20, $asHtml = false)
	{
		$params = array(
			'topicId'           => (string)$topicId,
			'posts_per_request' => $limit,
			'return_html'       => $asHtml
		);

		$response = $this->transport->call('get_thread_by_unread', $params);
		$this->assert()->noResultState($response);

		return PositionedPostResult::fromResponse($response);
	}


	/**
	 * Get Posts started by a former post
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=4#get_thread_by_post
	 * @param $postId
	 * @param int $limit
	 * @param bool $asHtml
	 * @return PositionedPostResult|PostFull[]
	 */
	public function getPostsStartByPost($postId, $limit = 20, $asHtml = false)
	{
		$params = array(
			'post_id'           => (string)$postId,
			'posts_per_request' => $limit,
			'return_html'       => $asHtml
		);

		$response = $this->transport->call('get_thread_by_post', $params);
		$this->assert()->noResultState($response);

		return PositionedPostResult::fromResponse($response);
	}


	/**
	 * Get the post as quote
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=4#get_quote_post
	 * @param $postId
	 * @return PostQuote
	 */
	public function getPostQuote($postId)
	{
		$response = $this->transport->call('get_quote_post', array('post_id' => (string)$postId));
		$this->assert()->noResultState($response);

		return PostQuote::fromResponse($response);
	}


	/**
	 * Get a post with saw content, useful for creating edit form
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=4#get_raw_post
	 * @param $postId
	 * @return RawPost
	 */
	public function getRawPost($postId)
	{
		$response = $this->transport->call('get_raw_post', array('post_id' => (string)$postId));
		$this->assert()->noResultState($response);

		return RawPost::fromResponse($response);
	}

	/**
	 * Update an existing post
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=4#save_raw_post
	 * @param $postId
	 * @param $title
	 * @param $content
	 * @param bool $returnAsHtml
	 * @param array|null $attachmentIds
	 * @param null $groupId
	 * @param null $reason
	 * @return array
	 */
	public function updatePost($postId, $title, $content, $returnAsHtml = false, array $attachmentIds = null, $groupId = null, $reason = null)
	{
		$method = $this->transport->createMethodCall('save_raw_post')
			->set('post_id', (string)$postId)
			->set('post_title', $title, true)
			->set('post_content', $content, true);

		if($attachmentIds || $reason) {
			$method
				->set('return_html', $returnAsHtml)
				->set('attachmentIds', (array)$attachmentIds)
				->set('group_id', (string)$groupId);
		}

		if($reason) {
			$method->set('reason', $reason, true);
		}

		$response = $method->call();
		$this->assert()->resultSuccess($response);

		return array(
			'state'   => $response->get('state') ? static::STATE_MOD_PUBLISHING_REQUIRED : static::STATE_PUBLISHED,
			'content' => $response->get('post_content', true)
		);
	}


	/**
	 * @param null $keywords
	 * @param int $limit
	 * @param int $offset
	 * @param null $searchId
	 * @return SearchResult|SearchPost[]
	 */
	public function search($keywords = null, $limit = 20, $offset = 0, $searchId = null)
	{
		$method = $this->transport->createMethodCall('search_post')
			->set('search_string', $keywords, true)
			->set('start_num', $offset)
			->set('last_num', $offset + $limit - 1);

		if($searchId) {
			$method->set('search_id', (string)$searchId);
		}

		$response = $method->call();
		$this->assert()->noResultState($response);

		$items  = $this->createSearchResultPosts($response->get('posts'));
		$result = new SearchResult($items, $response->get('total_post_num'), $offset, $response->get('search_id'));

		return $result;
	}


	/**
	 * @param array $filters
	 * @param int $limit
	 * @param int $offset
	 * @param null $searchId
	 * @return SearchResult|SearchPost[]
	 */
	public function advancedSearch(array $filters, $limit = 20, $offset = 20, $searchId = null)
	{
		$this->assert()->featureSupported(Features::ADVANCED_SEARCH);

		$method = $this->transport->createMethodCall('search', array('filters' => array('showposts' => 1)));
		AdvancedSearch::applyFilters($method, $filters, $limit, $offset, $searchId);

		$response = $method->call();
		$this->assert()->noResultState($response);

		$items  = $this->createSearchResultPosts($response->get('posts'));
		$result = new SearchResult($items, $response->get('total_post_num'), $offset, $response->get('search_id'));

		return $result;
	}


	/**
	 * @param $postId
	 * @param bool $like
	 */
	public function likePost($postId, $like = true)
	{
		$this->assert()->pushTypeIsEnabled(Config::PUSH_LIKE);

		$method = $like ? 'like_post' : 'unlike_post';

		$response = $this->transport->call($method, array('post_id' => (string)$postId));
		$this->assert()->resultSuccess($response);
	}


	/**
	 * @param $postId
	 */
	public function thankForPost($postId)
	{
		$this->assert()->pushTypeIsEnabled(Config::PUSH_THANK);

		$response = $this->transport->call('thank_post', array('post_id' => (string)$postId));
		$this->assert()->resultSuccess($response);
	}


	/**
	 * @param $forumId
	 * @param $topicId
	 * @param $body
	 * @param null $subject
	 * @param null $attachmentIds
	 * @param null $groupId
	 * @return array
	 */
	public function replyTo($forumId, $topicId, $body, $subject = null, $attachmentIds = null, $groupId = null)
	{
		$request = $this->transport->createMethodCall('reply_post')
			->set('forum_id', (string)$forumId)
			->set('topic_id', (string)$topicId)
			->set('subject', $subject, true)
			->set('text_body', $body, true);

		if($attachmentIds) {
			$request->set('attachment_id_array', array_map('strval', (array)$attachmentIds));
			$request->set('group_id', (string)$groupId);
		}

		$response = $request->call();
		$this->assert()->resultSuccess($response);

		return array(
			'post'  => Post::fromResponse($response),
			'state' => $response->get('state') ? static::STATE_MOD_PUBLISHING_REQUIRED : static::STATE_PUBLISHED
		);
	}


	/**
	 * @param $postId
	 * @param null $reason
	 */
	public function reportPost($postId, $reason = null)
	{
		$method = $this->transport->createMethodCall('report_post', array('post_id' => (string)$postId));

		if($reason) {
			$method->set('reason', $reason, true);
		}

		$response = $method->call();
		$this->assert()->featureSupported($response);
	}


	/**
	 * @param array $result
	 * @return SearchPost[]
	 */
	private function createSearchResultPosts($result)
	{
		$posts = array();

		foreach($result as $post) {
			$posts[] = SearchPost::fromResponse($post);
		}

		return $posts;
	}

} 
