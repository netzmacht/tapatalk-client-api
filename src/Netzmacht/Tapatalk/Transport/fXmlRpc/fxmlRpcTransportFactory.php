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
use fXmlRpc\Transport\HttpAdapterTransport;
use fXmlRpc\Transport\TransportInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie\CookieJar;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Netzmacht\Tapatalk\Transport\Guzzle\GuzzleUploadHandler;
use Netzmacht\Tapatalk\Transport\Serializer;
use Netzmacht\Tapatalk\Transport\UploadHandler;


/**
 * Class fxmlRpcTransportFactory
 * @package Netzmacht\Tapatalk\Transport
 */
class fxmlRpcTransportFactory
{
	/**
	 * @var TransportInterface
	 */
	private $httpTransport;

	/**
	 * @var UploadHandler
	 */
	private $uploadHandler;

	/**
	 * @var string
	 */
	private $uri;

	/**
	 * @var GuzzleClient
	 */
	private $httpClient;

	/**
	 * @var Serializer
	 */
	private $serializer;


	/**
	 * @param string $uri
	 */
	public function setUri($uri)
	{
		$this->uri = $uri;
	}


	/**
	 * @return string
	 */
	public function getUri()
	{
		return $this->uri;
	}


	/**
	 * @param \fXmlRpc\Transport\TransportInterface $httpTransport
	 */
	public function setHttpTransport($httpTransport)
	{
		$this->httpTransport = $httpTransport;
	}


	/**
	 * @return \fXmlRpc\Transport\TransportInterface
	 */
	public function getHttpTransport()
	{
		return $this->httpTransport;
	}

	/**
	 * @param GuzzleClient $httpClient
	 */
	public function setHttpClient($httpClient)
	{
		$this->httpClient = $httpClient;
	}

	/**
	 * @return GuzzleClient
	 */
	public function getHttpClient()
	{
		return $this->httpClient;
	}

	/**
	 * @param \Netzmacht\Tapatalk\Transport\Serializer $serializer
	 */
	public function setSerializer($serializer)
	{
		$this->serializer = $serializer;
	}

	/**
	 * @return \Netzmacht\Tapatalk\Transport\Serializer
	 */
	public function getSerializer()
	{
		return $this->serializer;
	}


	/**
	 * @param UploadHandler $uploadHandler
	 */
	public function setUploadHandler($uploadHandler)
	{
		$this->uploadHandler = $uploadHandler;
	}


	/**
	 * @return UploadHandler
	 */
	public function getUploadHandler()
	{
		return $this->uploadHandler;
	}


	/**
	 * @return fXmlRpcTransport
	 */
	public function create()
	{
		$this->createHttpTransport();
		$this->createHttpUploadHandler();
		$this->createSerializer();

		return new fXmlRpcTransport(new Client($this->uri, $this->httpTransport), $this->uploadHandler, $this->serializer);
	}


	/**
	 * Create Http transport if none given
	 */
	private function createHttpTransport()
	{
		if(!$this->httpTransport) {
			$client    = new GuzzleClient(['cookies' => new CookieJar()]);
			$transport = new HttpAdapterTransport(
				new GuzzleMessageFactory(),
				new \Http\Adapter\Guzzle6\Client($client)
			);

			$this->httpClient    = $client;
			$this->httpTransport = $transport;
		}
	}


	/**
	 * @throws \RuntimeException
	 */
	private function createHttpUploadHandler()
	{
		if(!$this->uploadHandler && $this->httpClient) {
			$this->uploadHandler = new GuzzleUploadHandler($this->httpClient);
		} elseif(!$this->uploadHandler) {
			throw new \RuntimeException('Not file uploader found');
		}
	}


	/**
	 *
	 */
	private function createSerializer()
	{
		if(!$this->serializer) {
			$this->serializer = new fXmlRpcSerializer();
		}
	}

} 
