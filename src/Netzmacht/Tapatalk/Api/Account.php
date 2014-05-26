<?php

namespace Netzmacht\Tapatalk\Api;


use Netzmacht\Tapatalk\Api\Exception\InvalidResponseException;
use Netzmacht\Tapatalk\Api;
use Netzmacht\Tapatalk\Transport;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Account extends Api
{
	const PERM_PRIVATE_MESSAGES      = 'can_pm';
	const PERM_SEND_PRIVATE_MESSAGES = 'can_send_pm';
	const PERM_IS_MODERATOR          = 'can_moderate';
	CONST PERM_SEARCH                = 'can_search';
	CONST PERM_WHO_IS_ONLINE         = 'can_whosonline';
	CONST PERM_PROFILE               = 'can_profile';
	CONST PERM_UPLOAD_AVATAR         = 'can_upload_avatar';

	const TYPE_BANNED     = 'banned';
	const TYPE_UNAPPROVED = 'unapproved';
	const TYPE_INACTIVE   = 'inactive';
	const TYPE_NORMAL     = 'normal';
	const TYPE_MODERATOR  = 'mod';
	const TYPE_ADMIN      = 'admin';

	const IMAGE_PNG = 'png';
	const IMAGE_JPG = 'jpg';


	/**
	 * @var bool
	 */
	private $loggedIn = false;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var int
	 */
	private $groupIds;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $avatar;

	/**
	 * @var int
	 */
	private $posts;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var array
	 */
	private $permissions = array();

	/**
	 * @var int
	 */
	private $unreadMessages;

	/**
	 * @var int
	 */
	private $unreadTopics;

	/**
	 * @var int
	 */
	private $maxAttachments;

	/**
	 * @var array
	 */
	private $maxImageSizes = array();

	/**
	 * @var array
	 */
	private $ignoredUserIds = array();

	/**
	 * @var string
	 */
	private $loginName;


	/**
	 * @param $username
	 * @param $password
	 * @throws InvalidResponseException
	 */
	public function login($username, $password)
	{
		$response = $this->transport->createMethodCall('login')
			->set('login_name', $username, true)
			->set('password', $password, true)
			->call();

		$this->assert()->resultSuccess($response);

		$this->loadAttributes($response);
		$this->loadPermission($response);
		$this->loadImageSizeDefinitions($response);

		$this->loggedIn = true;
	}


	/**
	 * Logout the user
	 */
	public function logout()
	{
		$this->assertIsLoggedIn();

		$this->loggedIn = false;
		$this->transport->call('logout_user');
	}


	/**
	 * @param $userId
	 * @param bool $ignore
	 */
	public function ignoreUser($userId, $ignore=true)
	{
		$this->assertIsLoggedIn();

		$this->transport->call('ignore_user', array(
			'user_id' => (string) $userId,
			'mode'    => $ignore ? 1 :0
		));

		if(!$ignore && in_array($userId, $this->ignoredUserIds)) {
			$key = array_search($userId, $this->ignoredUserIds);
			unset($this->ignoredUserIds[$key]);
			$this->ignoredUserIds = array_values($this->ignoredUserIds);
		}
		elseif($ignore && !in_array($userId, $this->ignoredUserIds)) {
			$this->ignoredUserIds[] = $userId;
		}
	}


	/**
	 * Check if account has permission. Use const PERM_* as action name
	 *
	 * @param $action
	 * @return bool
	 */
	public function hasPermission($action)
	{
		if(isset($this->permissions[$action])) {
			return $this->permissions[$action];
		}

		return false;
	}


	/**
	 * @return int
	 */
	public function getUnreadMessageNumber()
	{
		$this->lazyCallInboxStat();

		return $this->unreadMessages;
	}


	/**
	 * @return int
	 */
	public function getUnreadSubscribedTopicsNumber()
	{
		$this->lazyCallInboxStat();

		return $this->unreadTopics;
	}


	/**
	 * @return string
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}


	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}


	/**
	 * @return int
	 */
	public function getGroupIds()
	{
		return $this->groupIds;
	}


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return array
	 */
	public function getIgnoredUserIds()
	{
		return $this->ignoredUserIds;
	}


	/**
	 * @param $id
	 * @return bool
	 */
	public function isUserIgnored($id)
	{
		return in_array($id, $this->ignoredUserIds);
	}


	/**
	 * @return boolean
	 */
	public function isLoggedIn()
	{
		return $this->loggedIn;
	}


	/**
	 * @return int
	 */
	public function getMaxAttachments()
	{
		return $this->maxAttachments;
	}

	/**
	 * @param $type
	 * @return int
	 */
	public function getMaxImageSize($type)
	{
		if(isset($this->maxImageSizes[$type])) {
			return $this->maxImageSizes[$type];
		}

		return null;
	}


	/**
	 * @return int
	 */
	public function getPostsNumber()
	{
		return $this->posts;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}


	/**
	 * lazy call inbox stat
	 */
	private function lazyCallInboxStat()
	{
		if($this->unreadMessages === null) {
			$this->assertIsLoggedIn();

			$response = $this->transport->call('get_inbox_stat');

			$this->unreadMessages = $response['inbox_unread_count'];
			$this->unreadTopics   = $response['subscribed_topic_unread_count'];
		}
	}

	/**
	 * @param $response
	 */
	private function loadAttributes(MethodCallResponse $response)
	{
		$this->id             = $response->get('user_id');
		$this->username       = $response->get('username');
		$this->loginName      = $response->get('login_name');
		$this->groupIds       = $response->get('usergroup_id');
		$this->email          = $response->get('email', true);
		$this->avatar         = $response->get('icon_url');
		$this->posts          = $response->get('post_count');
		$this->type           = $response->get('user_type');
		$this->maxAttachments = $response->get('max_attachments');
		$this->ignoredUserIds = array_filter(explode(',', $response->get('ignored_uids')));
	}

	/**
	 * @param MethodCallResponse $response
	 */
	private function loadPermission(MethodCallResponse $response)
	{
		$this->permissions[static::PERM_IS_MODERATOR]          = $response->get(static::PERM_IS_MODERATOR, false, false);
		$this->permissions[static::PERM_PRIVATE_MESSAGES]      = $response->get(static::PERM_PRIVATE_MESSAGES, false, false);
		$this->permissions[static::PERM_SEND_PRIVATE_MESSAGES] = $response->get(static::PERM_SEND_PRIVATE_MESSAGES, false, false);
		$this->permissions[static::PERM_PROFILE]               = $response->get(static::PERM_PROFILE, false, false);
		$this->permissions[static::PERM_SEARCH]                = $response->get(static::PERM_SEARCH, false, false);
		$this->permissions[static::PERM_UPLOAD_AVATAR]         = $response->get(static::PERM_UPLOAD_AVATAR, false, false);
		$this->permissions[static::PERM_WHO_IS_ONLINE]         = $response->get(static::PERM_WHO_IS_ONLINE, false, false);
	}

	/**
	 * @param $response
	 */
	private function loadImageSizeDefinitions(MethodCallResponse $response)
	{
		$this->maxImageSizes[static::IMAGE_PNG] = $response->get('max_png_size');
		$this->maxImageSizes[static::IMAGE_JPG] = $response->get('max_jpg_size');
	}


	/**
	 * @throws Exception\PermissionDeniedException
	 */
	private function assertIsLoggedIn()
	{
		if(!$this->loggedIn) {
			throw new Api\Exception\PermissionDeniedException('User is not logged in');
		}
	}

}