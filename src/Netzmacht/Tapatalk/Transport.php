<?php

namespace Netzmacht\Tapatalk;


use Netzmacht\Tapatalk\Transport\MethodCall;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Transport\UploadHandler;


abstract class Transport implements UploadHandler
{

	/**
	 * @param string $method
	 * @param array $params
	 * @return MethodCallResponse
	 */
	abstract public function call($method, $params=array());


	/**
	 * Pass a request
	 * @param MethodCall $request
	 * @return MethodCallResponse
	 */
	public function request(MethodCall $request)
	{
		return $this->call($request->getMethod(), $request->getParams());
	}


	/**
	 * @param $method
	 * @param array $params
	 * @return MethodCall
	 */
	abstract public function createMethodCall($method, array $params=array());

} 