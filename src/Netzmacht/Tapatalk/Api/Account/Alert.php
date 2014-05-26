<?php

namespace Netzmacht\Tapatalk\Api\Account;


use Netzmacht\Tapatalk\Api\Board\Activity;
use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\DateTime;


/**
 * Class Alert
 * @package Netzmacht\Tapatalk\Api\Board
 */
class Alert extends Activity
{
	const TYPE_POST = 'post';
	const TYPE_USER = 'user';
	const TYPE_PRIVATE_MESSAGE = 'om';


	/**
	 * @var
	 */
	private $unread;

	/**
	 * @var
	 */
	private $position;

	/**
	 * @var
	 */
	private $topicId;

	/**
	 * @param $message
	 * @param $contentId
	 * @param $contentType
	 * @param $unread
	 * @param $user
	 * @param $topicId
	 * @param $position
	 * @param $createdAt
	 */
	function __construct($message, $contentId, $contentType, $unread, $user, $topicId, $position, $createdAt)
	{
		parent::__construct($message, $contentId, $contentType, $user, $createdAt);

		$this->unread      = $unread;
		$this->topicId     = $topicId;
		$this->position    = $position;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('message', true),
			$response->get('content_id'),
			$response->get('content_type'),
			$response->get('unread'),
			new User($response->get('user_id'), $response->get('username', true), $response->get('icon_url')),
			$response->get('topic_id'),
			$response->get('position'),
			DateTime::createFromTimestamp($response->get('timestamp'))
		);
	}

} 