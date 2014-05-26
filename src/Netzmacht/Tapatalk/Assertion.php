<?php

namespace Netzmacht\Tapatalk;

use Netzmacht\Tapatalk\Api\Exception\PermissionDeniedException;
use Netzmacht\Tapatalk\Api\Exception\ResponseException;
use Netzmacht\Tapatalk\Api\Exception\UnsupportedFeatureException;
use Netzmacht\Tapatalk\Client;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Assertion
{
	/**
	 * @var Client
	 */
	private $client;


	/**
	 * @param $client
	 */
	function __construct($client)
	{
		$this->client = $client;
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
		if(!$this->client->config()->isSupported($feature)) {
			throw new UnsupportedFeatureException('Board does not support feature: ' . $feature);
		}
	}


	/**
	 * @param $permission
	 * @throws \Netzmacht\Tapatalk\Api\Exception\PermissionDeniedException
	 */
	public function hasPermission($permission)
	{
		if(!$this->client->config()->hasPermission($permission)) {
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

} 