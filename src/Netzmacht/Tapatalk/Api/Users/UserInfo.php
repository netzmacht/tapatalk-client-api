<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Users;


use Netzmacht\Tapatalk\Util\DateTime;
use Netzmacht\Tapatalk\Transport;

class UserInfo extends User
{

	/**
	 * @var int
	 */
	private $posts;

	/**
	 * @var \DateTime
	 */
	private $registeredAt;

	/**
	 * @var \DateTime
	 */
	private $lastActivityAt;

	/**
	 * @var bool
	 */
	private $isOnline;

	/**
	 * @var bool
	 */
	private $acceptPrivateMessages;

	/**
	 * @var bool
	 */
	private $doIFollow;

	/**
	 * @var bool
	 */
	private $doYouFollow;

	/**
	 * @var bool
	 */
	private $acceptFollow;

	/**
	 * @var int
	 */
	private $follower;

	/**
	 * @var string
	 */
	private $displayText;

	/**
	 * @var
	 */
	private $currentAction;

	/**
	 * @var
	 */
	private $topicId;

	/**
	 * @var
	 */
	private $customFields;

	/**
	 * @param int $userId
	 * @param string $username
	 * @param string $avatar
	 * @param $acceptFollow
	 * @param $acceptPrivateMessages
	 * @param $currentAction
	 * @param $customFields
	 * @param $displayText
	 * @param $doIFollow
	 * @param $doYouFollow
	 * @param $follower
	 * @param $isOnline
	 * @param $lastActivityAt
	 * @param $posts
	 * @param $registeredAt
	 * @param $topicId
	 */
	function __construct(
		$userId,
		$username,
		$avatar,
		$isOnline,
		$posts,
		$registeredAt,
		$lastActivityAt,
		$currentAction,
		$acceptFollow,
		$acceptPrivateMessages,
		$customFields,
		$displayText,
		$doIFollow,
		$doYouFollow,
		$follower,
		$topicId
	) {
		parent::__construct($userId, $username, $avatar);

		$this->acceptFollow          = $acceptFollow;
		$this->acceptPrivateMessages = $acceptPrivateMessages;
		$this->currentAction         = $currentAction;
		$this->customFields          = $customFields;
		$this->displayText           = $displayText;
		$this->doIFollow             = $doIFollow;
		$this->doYouFollow           = $doYouFollow;
		$this->follower              = $follower;
		$this->isOnline              = $isOnline;
		$this->lastActivityAt        = $lastActivityAt;
		$this->posts                 = $posts;
		$this->registeredAt          = $registeredAt;
		$this->topicId               = $topicId;
	}


	/**
	 * @param Transport $transport
	 * @param $response
	 * @return UserInfo
	 */
	public static function fromResponse(Transport $transport, $response)
	{
		return new static(
			$response['user_id'],
			$transport->deserializeValue($response['username']),
			$transport->optionalValue($response, 'icon_url'),
			$transport->optionalValue($response, 'is_online', false, false),
			$response['post_count'],
			static::buildRegisteredAt($response),
			$transport->optionalValue($response, 'last_activity_time'),
			$transport->optionalValue($response, 'current_action', true),
			$transport->optionalValue($response, 'accept_follow', false, true),
			$transport->optionalValue($response, 'accept_pm'),
			static::buildCustomFields($transport, $response),
			$transport->optionalValue($response, 'display_text', true),
			$transport->optionalValue($response, 'i_follow_u', false, false),
			$transport->optionalValue($response, 'u_follow_me', false, false),
			$transport->optionalValue($response, 'follower', false, 0),
			$transport->optionalValue($response, 'topic_id')
		);
	}


	/**
	 * @param Transport $transport
	 * @param $response
	 * @return array
	 */
	private static function buildCustomFields(Transport $transport, $response)
	{
		$customFields = array();

		if(array_key_exists('custom_fields_list', $response)) {
			foreach($response['custom_fields_list'] as $field) {
				$name  = $transport->deserializeValue($field['name']);
				$value = $transport->deserializeValue($field['value']);

				$customFields[$name] = $value;
			}
		}

		return $customFields;
	}


	/**
	 * @param $response
	 * @return \DateTime|null
	 */
	private static function buildRegisteredAt($response)
	{
		if($response['reg_time']) {
			$tstamp = $response['reg_time'];
		}
		elseif(array_key_exists('timestamp_reg', $response)) {
			$tstamp = $response['timestamp_reg'];
		}
		else {
			return null;
		}

		return DateTime::createFromTimestamp($tstamp);
	}


	/**
	 * @return boolean
	 */
	public function isFollowAccepted()
	{
		return $this->acceptFollow;
	}


	/**
	 * @return boolean
	 */
	public function isPrivateMessageAccepted()
	{
		return $this->acceptPrivateMessages;
	}


	/**
	 * @return mixed
	 */
	public function getCurrentAction()
	{
		return $this->currentAction;
	}

	/**
	 * @return mixed
	 */
	public function getCustomFields()
	{
		return $this->customFields;
	}


	/**
	 * @param string $name
	 * @param null $default
	 * @return mixed
	 */
	public function getCustomField($name, $default=null)
	{
		if(isset($this->customFields[$name])) {
			return $this->customFields[$name];
		}

		return $default;
	}


	/**
	 * @return string
	 */
	public function getDisplayText()
	{
		return $this->displayText;
	}


	/**
	 * @return mixed
	 */
	public function getCurrentTopicId()
	{
		return $this->topicId;
	}


	/**
	 * @return boolean
	 */
	public function isFollower()
	{
		return $this->doIFollow;
	}

	/**
	 * @return boolean
	 */
	public function isFollowed()
	{
		return $this->doYouFollow;
	}

	/**
	 * @return int
	 */
	public function getFollowerNumber()
	{
		return $this->follower;
	}


	/**
	 * @return boolean
	 */
	public function isOnline()
	{
		return $this->isOnline;
	}


	/**
	 * @return \DateTime
	 */
	public function getLastActivityAt()
	{
		return $this->lastActivityAt;
	}


	/**
	 * @return int
	 */
	public function getPostsNumber()
	{
		return $this->posts;
	}


	/**
	 * @return \DateTime
	 */
	public function getRegisteredAt()
	{
		return $this->registeredAt;
	}

} 