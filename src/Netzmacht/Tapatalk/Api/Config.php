<?php

namespace Netzmacht\Tapatalk\Api;


use Netzmacht\Tapatalk\Transport;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Config
{
	const FEATURE_REPORT_POST             = 'report_post';
	const FEATURE_REPORT_PM               = 'report_pm';
	const FEATURE_MARK_ALL_READ           = 'mark_read';
	const FEATURE_MARK_FORUM_READ         = 'mark_forum';
	const FEATURE_SUBSCRIBE_FORUM         = 'subscribe_forum';
	const FEATURE_LIST_LATEST_TOPICS      = 'get_latest_topic';
	const FEATURE_GET_ID_BY_URL           = 'get_id_by_url';
	const FEATURE_DELETE_REASON           = 'delete_reason';
	const FEATURE_MOD_APPROVE_VIEW        = 'm_approve';
	const FEATURE_MOD_DELETE_VIEW         = 'm_delete';
	const FEATURE_MOD_REPORT_VIEW         = 'm_report';
	const FEATURE_ANONYMOUS_LOGIN         = 'anonymous';
	const FEATURE_SEARCH_ID               = 'searchid';
	const FEATURE_DOWNLOAD_AVATAR         = 'avatar';
	const FEATURE_PM_PAGINATION           = 'pm_load';
	const FEATURE_SUBSCRIBE_PAGINATION    = 'subscribe_load';
	const FEATURE_PM                      = 'inbox_stat';
	const FEATURE_SUBSCRIBE_UNREAD_NUMBER = 'inbox_stat'; // no bug. it's actually the same config name. added for more semantic
	const FEATURE_MULTI_QUOTE             = 'multi_quote';
	const FEATURE_DEFAULT_SMILIES         = 'default_smilies';
	const FEATURE_UNREAD                  = 'can_unread';
	const FEATURE_ANNOUNCEMENTS           = 'announcement';
	const FEATURE_EMOJI                   = 'emoji_support';
	const FEATURE_PM_CONVERSATION = 'conversation';
	const FEATURE_GET_TOPIC_STATUS = 'get_topic_status';
	const FEATURE_GET_PARTICIPATED_FORUM = 'get_participated_forum';
	const FEATURE_GET_FORUM_STATUS = 'get_forum_status';
	const FEATURE_GET_SMILIES = 'get_smilies';

	const PERM_GUEST_ACCESS = 'guest_okay';
	const PERM_GUEST_SEARCH = 'guest_search';
	const PERM_GUEST_WHO_IS_ONLINE = 'guest_whosonline';

	const ENCRYPTION_MD5 = 'md5';
	const ENCRYPTION_SHA1 = 'sha1';

	const PUSH_ANNOUNCMENT = 'ann';
	const PUSH_CONVERSATION = 'conv';
	const PUSH_PRIVATE_MESSAGE = 'pm';
	const PUSH_LIKE = 'like';
	const PUSH_THANK = 'thank';
	const PUSH_QUOTE = 'quote';
	const PUSH_NEW_TOPIC = 'newtopic';
	const PUSH_TAG = 'tag';
	const PUSH_SUB = 'sub'; // TODO whre it is used? Find a better name


	private $features = array();

	private $permissions = array();

	private $boardVersion;
	private $tapatalkVersion;
	private $apiVersion;
	private $isOpen;
	private $searchMinLength;
	private $passwordEncryption;

	private $pushTypes;

	/**
	 * @param $boardVersion
	 * @param $tapatalkVersion
	 * @param $apiVersion
	 * @param $isOpen
	 * @param $features
	 * @param $permissions
	 * @param $passwordEncryption
	 * @param $searchMinLength
	 * @param $pushTypes
	 */
	function __construct($boardVersion, $tapatalkVersion, $apiVersion, $isOpen, $features, $permissions, $passwordEncryption, $searchMinLength, $pushTypes)
	{
		$this->boardVersion       = $boardVersion;
		$this->tapatalkVersion    = $tapatalkVersion;
		$this->apiVersion         = $apiVersion;
		$this->isOpen             = $isOpen;
		$this->features           = $features;
		$this->permissions        = $permissions;
		$this->passwordEncryption = $passwordEncryption;
		$this->searchMinLength    = $searchMinLength;
		$this->pushTypes          = $pushTypes;
	}


	/**
	 * Create config by giving response
	 *
	 * @param MethodCallResponse $response
	 * @return Config
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		$features    = array();
		$permissions = array();
		$value       = function($name, $default=false) use($response) {
			return (bool) $response->get($name, false, $default);
		};

		$features[static::FEATURE_REPORT_POST]             = $value(static::FEATURE_REPORT_POST);
		$features[static::FEATURE_REPORT_PM]               = $value(static::FEATURE_REPORT_PM);
		$features[static::FEATURE_MARK_ALL_READ]           = $value(static::FEATURE_MARK_ALL_READ, true);
		$features[static::FEATURE_MARK_FORUM_READ]         = $value(static::FEATURE_MARK_FORUM_READ);
		$features[static::FEATURE_SUBSCRIBE_FORUM]         = $value(static::FEATURE_SUBSCRIBE_FORUM, true);
		$features[static::FEATURE_LIST_LATEST_TOPICS]      = $value(static::FEATURE_LIST_LATEST_TOPICS);
		$features[static::FEATURE_GET_ID_BY_URL]           = $value(static::FEATURE_GET_ID_BY_URL);
		$features[static::FEATURE_MOD_APPROVE_VIEW]        = $value(static::FEATURE_MOD_APPROVE_VIEW);
		$features[static::FEATURE_MOD_REPORT_VIEW]         = $value(static::FEATURE_MOD_DELETE_VIEW);
		$features[static::FEATURE_MOD_DELETE_VIEW]         = $value(static::FEATURE_MOD_REPORT_VIEW);
		$features[static::FEATURE_ANONYMOUS_LOGIN]         = $value(static::FEATURE_ANONYMOUS_LOGIN);
		$features[static::FEATURE_SEARCH_ID]               = $value(static::FEATURE_SEARCH_ID);
		$features[static::FEATURE_DOWNLOAD_AVATAR]         = $value(static::FEATURE_DOWNLOAD_AVATAR);
		$features[static::FEATURE_PM]                      = $value(static::FEATURE_PM);
		$features[static::FEATURE_SUBSCRIBE_UNREAD_NUMBER] = $value(static::FEATURE_SUBSCRIBE_UNREAD_NUMBER);
		$features[static::FEATURE_MULTI_QUOTE]             = $value(static::FEATURE_MULTI_QUOTE);
		$features[static::FEATURE_DEFAULT_SMILIES]         = $value(static::FEATURE_DEFAULT_SMILIES);
		$features[static::FEATURE_UNREAD]                  = $value(static::FEATURE_UNREAD, true);
		$features[static::FEATURE_ANNOUNCEMENTS]           = $value(static::FEATURE_ANNOUNCEMENTS, true);
		$features[static::FEATURE_EMOJI]                   = $value(static::FEATURE_EMOJI);
		$features[static::FEATURE_PM_CONVERSATION]         = $value(static::FEATURE_PM_CONVERSATION);
		$features[static::FEATURE_GET_TOPIC_STATUS]        = $value(static::FEATURE_GET_TOPIC_STATUS);
		$features[static::FEATURE_GET_PARTICIPATED_FORUM]  = $value(static::FEATURE_GET_PARTICIPATED_FORUM);
		$features[static::FEATURE_GET_FORUM_STATUS]        = $value(static::FEATURE_GET_FORUM_STATUS);
		$features[static::FEATURE_GET_SMILIES]        = $value(static::FEATURE_GET_SMILIES);

		// TODO go on with conversation

		// TODO: get_forum

		$permissions[static::PERM_GUEST_ACCESS]        = $value(static::PERM_GUEST_ACCESS);
		$permissions[static::PERM_GUEST_SEARCH]        = $value(static::PERM_GUEST_SEARCH);
		$permissions[static::PERM_GUEST_WHO_IS_ONLINE] = $value(static::PERM_GUEST_WHO_IS_ONLINE);

		$passwordEncryption = $value('support_sha1') ?: ($value('support_md5') ?: null);

		return new static(
			$response->get('sys_version'),
			$response->get('version'),
			$response->get('api_level'),
			$response->get('is_open'),
			$features,
			$permissions,
			$passwordEncryption,
			$value('min_search_length', 0),
			array_filter(explode(',', $response->get('push_type')))
		);
	}


	public function isOpen()
	{
		return $this->isOpen;
	}


	/**
	 * @param $key
	 * @return bool
	 */
	public function isSupported($key)
	{
		if(isset($this->features[$key])) {
			return $this->features[$key];
		}

		return false;
	}

	/**
	 * @param $key
	 * @return bool
	 */
	public function hasPermission($key) {
		if(array_key_exists($key, $this->permissions)) {
			return $this->permissions[$key];
		}

		return false;
	}


	/**
	 * @param $pushType
	 * @return bool
	 */
	public function isPushTypeEnabled($pushType)
	{
		return in_array($pushType, $this->pushTypes);
	}


	/**
	 * @return mixed
	 */
	public function getPushTypes()
	{
		return $this->pushTypes;
	}


	/**
	 * @return mixed
	 */
	public function getApiVersion()
	{
		return $this->apiVersion;
	}

	/**
	 * @return mixed
	 */
	public function getBoardVersion()
	{
		return $this->boardVersion;
	}

	/**
	 * @return mixed
	 */
	public function getSearchMinLength()
	{
		return $this->searchMinLength;
	}

	/**
	 * @return mixed
	 */
	public function getTapatalkVersion()
	{
		return $this->tapatalkVersion;
	}

	/**
	 * @return mixed
	 */
	public function getPasswordEncryption()
	{
		return $this->passwordEncryption;
	}

	/**
	 * @return array
	 */
	public function getFeatures()
	{
		return $this->features;
	}

	/**
	 * @return array
	 */
	public function getPermissions()
	{
		return $this->permissions;
	}

} 