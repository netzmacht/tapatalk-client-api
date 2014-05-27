<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Transport\fXmlRpc;

use fXmlRpc\Client;
use fXmlRpc\Exception\HttpException;
use fXmlRpc\Exception\ResponseException;
use fXmlRpc\Parser\XmlReaderParser;
use fXmlRpc\Value\Base64;
use fXmlRpc\Value\Base64Interface;
use Netzmacht\Tapatalk\Transport;
use Netzmacht\Tapatalk\Transport\MethodCall;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Transport\Serializer;
use Netzmacht\Tapatalk\Transport\UploadHandler;


/**
 * Class fXmlRpcTransport
 * @package Netzmacht\Tapatalk\Transport
 */
class fXmlRpcTransport extends Transport
{

	/**
	 * @var Client
	 */
	private $rpcClient;

	/**
	 * @var UploadHandler
	 */
	private $uploadHandler;

	/**
	 * @var Serializer
	 */
	private $serializer;


	/**
	 * @param $rpcClient
	 * @param $uploadHandler
	 * @param Serializer $serializer
	 */
	function __construct($rpcClient, $uploadHandler, Serializer $serializer)
	{
		$this->rpcClient     = $rpcClient;
		$this->uploadHandler = $uploadHandler;
		$this->serializer    = $serializer;
	}


	/**
	 * @param string $method
	 * @param array $params
	 * @return mixed
	 */
	public function call($method, $params = array())
	{
		$response = $this->rpcClient->call($method, $params);

		return new MethodCallResponse($this->serializer, $response);
	}


	/**
	 * Upload a file
	 *
	 * @param $path
	 * @param $file
	 * @param array $params
	 * @throws \fXmlRpc\Exception\ResponseException
	 * @return mixed
	 */
	public function upload($path, $file, $params = array())
	{
		$fault  = false;
		$parser = new XmlReaderParser();

		$response = $this->uploadHandler->upload($params, $file, $params);
		$response = $parser->parse($response, $fault);

		if($fault) {
			throw new ResponseException('Could not parse result');
		}
	}


	/**
	 * @param $method
	 * @param array $params
	 * @return MethodCall
	 */
	public function createMethodCall($method, array $params = array())
	{
		return new MethodCall($this, $this->serializer, $method, $params);
	}


}