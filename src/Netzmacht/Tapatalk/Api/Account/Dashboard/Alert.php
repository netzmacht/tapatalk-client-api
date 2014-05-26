<?php

namespace Netzmacht\Tapatalk\Api\Account\Dashboard;


use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\DateTime;

class Alert extends Feed
{
	/**
	 * @var bool
	 */
	private $unread;

	/**
	 * @param $unread
	 * @param $message
	 * @param $content
	 * @param $user
	 * @param $createdAt
	 * @param $topicId
	 * @param $postId
	 */
	function __construct($unread, $message, $content, User $user, $createdAt, $topicId, $postId)
	{
		parent::__construct($message, $content, $user, $createdAt, $topicId, $postId);
		$this->unread = $unread;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('new_alert'),
			$response->get('message', true),
			$response->get('short_content', true),
			new User($response->get('user_id'), $response->get('username', true), $response->get('icon_url')),
			DateTime::createFromTimestamp($response->get('post_time')),
			$response->get('topic_id'),
			$response->get('post_id')
		);
	}


	/**
	 * @return boolean
	 */
	public function unread()
	{
		return $this->unread;
	}

} 