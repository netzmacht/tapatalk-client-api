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


use Netzmacht\Tapatalk\Api\Account\Alert;
use Netzmacht\Tapatalk\Api\Account\LoginByTapatalkIdResult;
use Netzmacht\Tapatalk\Api\Exception\InvalidResponseException;
use Netzmacht\Tapatalk\Api;
use Netzmacht\Tapatalk\Result;
use Netzmacht\Tapatalk\Transport;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\Pagination;

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
	 * @var string
	 */
	private $tapatalkId;

	/**
	 * @var string
	 */
	private $tapatalkCode;


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
	 * Login by tapatalk id. This will auto register user if not exists
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=12#sign_in
	 * @param $token
	 * @param $code
	 * @param null $email
	 * @param null $username
	 * @param null $password
	 * @return LoginByTapatalkIdResult
	 */
	public function loginByTapatalkId($token, $code, $email=null, $username=null, $password=null)
	{
		$method = $this->transport->createMethodCall('sign_in')
			->set('token', $token)
			->set('code', $code);

		if($email || $username || $password) {
			$method->set('email', (string) $email, true);
		}

		if($username || $password) {
			$method->set('username', (string) $username, true);
		}

		if($password) {
			$method->set('password', (string) $password, true);
		}

		$response = $method->call();
		$this->assert()->noResultState($response);

		if(!$response->get('status')) {
			$this->loadAttributes($response);
			$this->loadPermission($response);
			$this->loadImageSizeDefinitions($response);

			$this->tapatalkId   = $token;
			$this->tapatalkCode = $code;

			$this->loggedIn = true;
		}

		return LoginByTapatalkIdResult::fromResponse($response);
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
	 * Update password of current user
	 *
	 * @param http://tapatalk.com/api/api_section.php?id=12#update_password
	 * @param $oldPassword
	 * @param $newPassword
	 */
	public function updatePassword($oldPassword, $newPassword)
	{
		$this->assertIsLoggedIn();

		$response = $this->transport->createMethodCall('update_password')
			->set('old_password', $oldPassword, true)
			->set('new_password', $newPassword, true)
			->call();

		$this->assert()->resultSuccess($response);
	}

	/**
	 * Update password of current user. If the user has logged in by tapatalk id, the confirmation of the current
	 * password is not required
	 *
	 * @param http://tapatalk.com/api/api_section.php?id=12#update_password
	 * @param $newPassword
	 */
	public function updatePasswordByTapatalkId($newPassword)
	{
		$this->assertLoggedInByTapatalkId();

		$response = $this->transport->createMethodCall('update_password')
			->set('new_password', $newPassword, true)
			->set('token', $this->tapatalkId)
			->set('code', $this->tapatalkCode)
			->call();

		$this->assert()->resultSuccess($response);
	}


	/**
	 * Update email of user account. Current forum password ist required
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=12#update_email
	 * @param string $newEmail
	 * @param string $password
	 */
	public function updateEmail($newEmail, $password)
	{
		$response = $this->transport->createMethodCall('update_email')
			->set('password', $password, true)
			->set('new_email', $newEmail, true)
			->call();

		$this->assert()->resultSuccess($response);
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=12#update_avatar
	 */
	public function updateAvatar()
	{
		// TODO: Implement
		trigger_error('Not implemented: ' . __METHOD__ , E_USER_ERROR);
	}


	/**
	 * Register a new user
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=12#register
	 * @param $username
	 * @param $password
	 * @param $email
	 * @param null $token
	 * @param null $code
	 * @return mixed|MethodCallResponse|Transport\MethodCallResponse[]
	 */
	public function registerUser($username, $password, $email, $token=null, $code=null)
	{
		$method = $this->transport->createMethodCall('register')
			->set('username', $username, true)
			->set('password', $password, true)
			->set('email', $email, true);

		if($token || $code) {
			$method->set('token', $token);
		}

		if($code) {
			$method->set('code', $code);
		}

		$response = $method->call();
		$this->assert()->resultSuccess($response);

		return $response->get('preview_topic_id');
	}


	/**
	 * @param $userId
	 * @param bool $ignore
	 */
	public function ignoreUser($userId, $ignore = true)
	{
		$this->assertIsLoggedIn();

		$this->transport->call('ignore_user', array(
			'user_id' => (string)$userId,
			'mode'    => $ignore ? 1 : 0
		));

		if(!$ignore && in_array($userId, $this->ignoredUserIds)) {
			$key = array_search($userId, $this->ignoredUserIds);
			unset($this->ignoredUserIds[$key]);
			$this->ignoredUserIds = array_values($this->ignoredUserIds);
		} elseif($ignore && !in_array($userId, $this->ignoredUserIds)) {
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
	 * @param bool $markAllAlertsAsRead
	 * @return static
	 */
	public function getDashboard($markAllAlertsAsRead = false)
	{
		$params                    = array();
		$params['alert_mark_read'] = $markAllAlertsAsRead;

		if($markAllAlertsAsRead) {

		}

		$response = $this->transport->call('get_dashboard', $params);
		$this->assert()->noResultState($response);

		return Account\Dashboard::fromResponse($response);
	}


	/**
	 * @param int $limit
	 * @param int $offset
	 * @return Result|\Netzmacht\Tapatalk\Api\Account\Alert[]
	 */
	public function getAlerts($limit = 20, $offset = 0)
	{
		$params = array(
			'page'    => Pagination::getPage($limit, $offset),
			'perpage' => $limit
		);

		$response = $this->transport->call('get_alert', $params);
		$this->assert()->noResultState($response);

		$alerts = array();

		foreach($response->get('items') as $alert) {
			$alerts[] = Alert::fromResponse($alert);
		}

		return new Result($alerts, $response->get('total'), $offset);
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

			$this->unreadMessages = $response->get('inbox_unread_count');
			$this->unreadTopics   = $response->get('subscribed_topic_unread_count');
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

	/**
	 * @throws Exception\PermissionDeniedException
	 */
	private function assertLoggedInByTapatalkId()
	{
		$this->assertIsLoggedIn();

		if(!$this->tapatalkId || !$this->tapatalkCode) {
			throw new Api\Exception\PermissionDeniedException('User was not logged in by tapatalk id');
		}
	}

}