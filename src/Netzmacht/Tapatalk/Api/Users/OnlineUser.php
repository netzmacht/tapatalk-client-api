<?php

namespace Netzmacht\Tapatalk\Api\Users;


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class OnlineUser extends User
{
	const DEVICE_BROWSER   = 'browser';
	const DEVICE_MOBILE    = 'mobile';
	const DEVICE_TAPATALK  = 'tapatalk';
	const DEVICE_OTHER_APP = 'byo';

	/**
	 * @var string
	 */
	private $displayText;

	/**
	 * @var int
	 */
	private $topicId;

	/**
	 * @var string
	 */
	private $device;

	/**
	 * @param int $userId
	 * @param string $username
	 * @param string $avatar
	 * @param int $device
	 * @param string $displayText
	 * @param string $topicId
	 */
	function __construct($userId, $username, $avatar, $device, $displayText, $topicId)
	{
		parent::__construct($userId, $username, $avatar);

		$this->device      = $device;
		$this->displayText = $displayText;
		$this->topicId     = $topicId;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return OnlineUser
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('user_id'),
			$response->get('username', true),
			$response->get('icon_url'),
			$response->get('from'),
			$response->get('display_text', true),
			$response->get('topic_id')
		);
	}


	/**
	 * @return string
	 */
	public function getDevice()
	{
		return $this->device;
	}

	/**
	 * @return string
	 */
	public function getDisplayText()
	{
		return $this->displayText;
	}

	/**
	 * @return int
	 */
	public function getTopicId()
	{
		return $this->topicId;
	}

} 