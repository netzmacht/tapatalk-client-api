<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Topics;


use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\DateTime;


class TopicStatus
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var bool
	 */
	private $isSubscribed;

	/**
	 * @var bool
	 */
	private $canSubscribe;

	/**
	 * @var bool
	 */
	private $isClosed;


	/**
	 * @var bool
	 */
	private $hasNewPosts;

	/**
	 * @var \DateTime
	 */
	private $lastReplyAt;

	/**
	 * @var int
	 */
	private $replyNumber;

	/**
	 * @var int
	 */
	private $viewNumber;


	/**
	 * @param $id
	 * @param $isClosed
	 * @param $isSubscribed
	 * @param $hasNewPosts
	 * @param $canSubscribe
	 * @param $lastReplyAt
	 * @param $replyNumber
	 * @param $viewNumber
	 */
	function __construct($id, $isClosed, $isSubscribed, $hasNewPosts, $canSubscribe, $lastReplyAt, $replyNumber, $viewNumber)
	{
		$this->id           = $id;
		$this->isClosed     = $isClosed;
		$this->isSubscribed = $isSubscribed;
		$this->hasNewPosts  = $hasNewPosts;
		$this->canSubscribe = $canSubscribe;
		$this->lastReplyAt  = $lastReplyAt;
		$this->replyNumber  = $replyNumber;
		$this->viewNumber   = $viewNumber;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return Topic
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('topic_id'),
			$response->get('is_closed', false, false),
			$response->get('is_subscribed', false, false),
			$response->get('new_post', false, false),
			$response->get('can_subscribe', false, true),
			DateTime::createFromTimestamp($response->get('last_reply_time') ?: $response->get('timestamp')),
			$response->get('reply_number'),
			$response->get('view_number')
		);
	}


	/**
	 * @return boolean
	 */
	public function hasNewPosts()
	{
		return $this->hasNewPosts;
	}


	/**
	 * @return boolean
	 */
	public function canSubscribe()
	{
		return $this->canSubscribe;
	}


	/**
	 * @return int
	 */
	public function getTopicId()
	{
		return $this->id;
	}


	/**
	 * @return boolean
	 */
	public function isClosed()
	{
		return $this->isClosed;
	}


	/**
	 * @return boolean
	 */
	public function isSubscribed()
	{
		return $this->isSubscribed;
	}


	/**
	 * @return \DateTime
	 */
	public function getLastReplyAt()
	{
		return $this->lastReplyAt;
	}


	/**
	 * @return int
	 */
	public function getReplyNumber()
	{
		return $this->replyNumber;
	}


	/**
	 * @return int
	 */
	public function getViewNumber()
	{
		return $this->viewNumber;
	}

} 