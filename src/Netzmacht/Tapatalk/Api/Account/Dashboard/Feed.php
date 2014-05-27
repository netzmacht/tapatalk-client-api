<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Account\Dashboard;


use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\DateTime;

class Feed
{
	/**
	 * @var int
	 */
	private $topicId;

	/**
	 * @var int
	 */
	private $postId;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var \DateTime
	 */
	private $createdAt;

	/**
	 * @param $message
	 * @param $content
	 * @param $user
	 * @param $createdAt
	 * @param $topicId
	 * @param $postId
	 */
	function __construct($message, $content, User $user, $createdAt, $topicId, $postId)
	{
		$this->message   = $message;
		$this->content   = $content;
		$this->user      = $user;
		$this->createdAt = $createdAt;
		$this->postId    = $postId;
		$this->topicId   = $topicId;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('message', true),
			$response->get('short_content', true),
			new User(null, $response->get('username', true), $response->get('icon_url')),
			DateTime::createFromTimestamp($response->get('post_time')),
			$response->get('topic_id'),
			$response->get('post_id')
		);
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return int
	 */
	public function getPostId()
	{
		return $this->postId;
	}

	/**
	 * @return int
	 */
	public function getTopicId()
	{
		return $this->topicId;
	}

	/**
	 * @return \Netzmacht\Tapatalk\Api\Users\User
	 */
	public function getUser()
	{
		return $this->user;
	}


}