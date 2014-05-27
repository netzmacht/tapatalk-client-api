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

use Netzmacht\Tapatalk\Api;
use Netzmacht\Tapatalk\Api\Forums\Forum;
use Netzmacht\Tapatalk\Api\Forums\ForumNode;
use Netzmacht\Tapatalk\Result;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Forums extends Api
{
	/**
	 * @param bool $forumId
	 * @param bool $descriptions
	 * @return ForumNode[]
	 */
	public function getForumTree($forumId = null, $descriptions = true)
	{
		$params = array(
			'return_description' => $descriptions,
		);

		if($forumId) {
			$params['forum_id'] = (string)$forumId;
		}

		$response = $this->transport->call('get_forum', $params);
		$this->assert()->noResultState($response);

		$forums = array();

		foreach($response as $forum) {
			$forums[] = ForumNode::fromResponse($forum);
		}

		return $forums;
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=1#get_participated_forum
	 * @return Forum[]|Result
	 */
	public function getParticipatedForums()
	{
		$response = $this->transport->call('get_participated_forum');
		$this->assert()->noResultState($response);

		return $this->createForumResult($response);
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=6#get_subscribed_forum
	 * @return Forum[]|Result
	 */
	public function getSubscribedForums()
	{
		$response = $this->transport->call('get_subscribed_forum');
		$this->assert()->noResultState($response);

		return $this->createForumResult($response);
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=1#get_forum_status
	 * @param array $forumIds
	 * @return Forum[]|Result
	 */
	public function getForums(array $forumIds)
	{
		$forumIds = array_map('strval', $forumIds);

		$response = $this->transport->call('get_forum_status', array('forum_ids' => $forumIds));
		$this->assert()->noResultState($response);

		return $this->createForumResult($response);
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=6#subscribe_forum
	 * @param $forumId
	 */
	public function subscribeForum($forumId)
	{
		$response = $this->transport->call('subscribe_forum', array('forum_id' => (string)$forumId));
		$this->assert()->resultSuccess($response);
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=6#unsubscribe_forum
	 * @param $forumId
	 */
	public function unsubscribeForum($forumId)
	{
		$response = $this->transport->call('unsubscribe_forum', array('forum_id' => (string)$forumId));
		$this->assert()->resultSuccess($response);
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=1#login_forum
	 * @param $forumId
	 * @param $password
	 */
	public function loginToProtectedForum($forumId, $password)
	{
		$response = $this->transport->createMethodCall('login_forum', array('forum_id' => (string)$forumId))
			->set('password', $password, true)
			->call();

		$this->assert()->resultSuccess($response);
	}


	/**
	 * @see  http://tapatalk.com/api/api_section.php?id=1#mark_all_as_read
	 * @param int $forumId
	 */
	public function markForumAsRead($forumId)
	{
		$response = $this->transport->call('mark_all_as_read', array('forum_id' => (string)$forumId));
		$this->assert()->resultSuccess($response);
	}

	/**
	 * @param MethodCallResponse $response
	 * @return Result
	 */
	private function createForumResult(MethodCallResponse $response)
	{
		$forums = array();

		foreach($response->get('forums') as $forum) {
			$forums[] = Forum::fromResponse($forum);
		}

		return new Result($forums, $response->get('total_forums_num'));
	}

}