<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk;

use Netzmacht\Tapatalk\Api\Config;
use Netzmacht\Tapatalk\Api\Exception\PermissionDeniedException;
use Netzmacht\Tapatalk\Api\Exception\ResponseException;
use Netzmacht\Tapatalk\Api\Exception\UnsupportedFeatureException;
use Netzmacht\Tapatalk\Api\Exception\DisabledPushTypeException;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Assertion
{
	/**
	 * @var Config
	 */
	private $config;


	/**
	 * @param $config
	 */
	function __construct($config)
	{
		$this->config = $config;
	}


	/**
	 * @param $response
	 */
	public function noResultState(MethodCallResponse $response)
	{
		if($response->has('result') && !$response->get('result')) {
			$this->createException($response->get('result_text', true));
		}
	}


	/**
	 * @param $response
	 */
	public function resultSuccess(MethodCallResponse $response)
	{
		if(!$response->get('result')) {
			$this->createException($response->get('result_text', true));
		}
	}


	/**
	 * @param $feature
	 * @throws \Netzmacht\Tapatalk\Api\Exception\UnsupportedFeatureException
	 */
	public function featureSupported($feature)
	{
		if(!$this->config->isSupported($feature)) {
			throw new UnsupportedFeatureException('Board does not support feature: ' . $feature);
		}
	}


	/**
	 * @param $permission
	 * @throws \Netzmacht\Tapatalk\Api\Exception\PermissionDeniedException
	 */
	public function hasPermission($permission)
	{
		if(!$this->config->hasPermission($permission)) {
			throw new PermissionDeniedException('Permission "' . $permission . '" not granted');
		}
	}


	/**
	 * @param $message
	 * @throws PermissionDeniedException
	 * @throws ResponseException
	 */
	private function createException($message)
	{
		if(strpos($message, 'not have permission') !== false) {
			throw new PermissionDeniedException($message);
		}
		else {
			throw new ResponseException($message);
		}
	}

	public function pushTypeIsEnabled($type)
	{
		if(!$this->config->isPushTypeEnabled($type)) {
			throw new DisabledPushTypeException('Push type "' . $type . ' " is not enabled.');
		}
	}

} 