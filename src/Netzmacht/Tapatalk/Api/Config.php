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


use Netzmacht\Tapatalk\Api\Config\Features;
use Netzmacht\Tapatalk\Transport;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Config
{
	const PERM_GUEST_ACCESS        = 'guest_okay';
	const PERM_GUEST_SEARCH        = 'guest_search';
	const PERM_GUEST_WHO_IS_ONLINE = 'guest_whosonline';

	const ENCRYPTION_MD5  = 'md5';
	const ENCRYPTION_SHA1 = 'sha1';

	const PUSH_ANNOUNCMENT     = 'ann';
	const PUSH_CONVERSATION    = 'conv';
	const PUSH_PRIVATE_MESSAGE = 'pm';
	const PUSH_LIKE            = 'like';
	const PUSH_THANK           = 'thank';
	const PUSH_QUOTE           = 'quote';
	const PUSH_NEW_TOPIC       = 'newtopic';
	const PUSH_TAG             = 'tag';
	const PUSH_SUB             = 'sub'; // TODO whre it is used? Find a better name


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
		$passwordEncryption = (bool) $response->get('support_sha1') ?: ($response->get('support_md5') ? : null);

		return new static(
			$response->get('sys_version'),
			$response->get('version'),
			$response->get('api_level'),
			$response->get('is_open'),
			self::loadFeatures($response),
			self::loadPermissions($response),
			$passwordEncryption,
			$response->get('min_search_length', false, 0),
			array_filter(explode(',', $response->get('push_type')))
		);
	}

	/**
	 * @param MethodCallResponse $response
	 * @return mixed
	 */
	private static function loadPermissions(MethodCallResponse $response)
	{
		$permissions = array();
		$value       = function ($name, $default = false) use ($response) {
			return (bool)$response->get($name, false, $default);
		};

		$permissions[static::PERM_GUEST_ACCESS]        = $value(static::PERM_GUEST_ACCESS);
		$permissions[static::PERM_GUEST_SEARCH]        = $value(static::PERM_GUEST_SEARCH);
		$permissions[static::PERM_GUEST_WHO_IS_ONLINE] = $value(static::PERM_GUEST_WHO_IS_ONLINE);

		return $permissions;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return mixed
	 */
	private static function loadFeatures(MethodCallResponse $response)
	{
		$features = array();

		foreach(Features::getFeatures() as $feature) {
			$features[$feature] = (bool) $response->get($feature, false, Features::getDefaultValue($feature));
		}

		return $features;
	}

	/**
	 * @return bool
	 */
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
	public function hasPermission($key)
	{
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