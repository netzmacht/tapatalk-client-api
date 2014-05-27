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
use fXmlRpc\Transport\GuzzleBridge;
use fXmlRpc\Transport\TransportInterface;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Plugin\Cookie\CookiePlugin;
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
	private $guzzle;

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
	 * @param \Guzzle\Http\Client $guzzle
	 */
	public function setGuzzle($guzzle)
	{
		$this->guzzle = $guzzle;
	}

	/**
	 * @return \Guzzle\Http\Client
	 */
	public function getGuzzle()
	{
		return $this->guzzle;
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
	 * @param \Netzmacht\Tapatalk\Transport\UploadHandler $uploadHandler
	 */
	public function setUploadHandler($uploadHandler)
	{
		$this->uploadHandler = $uploadHandler;
	}


	/**
	 * @return \Netzmacht\Tapatalk\Transport\UploadHandler
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
			$guzzle = new GuzzleClient();
			$cookie = new CookiePlugin(new ArrayCookieJar());
			$guzzle->addSubscriber($cookie);

			$transport = new GuzzleBridge($guzzle);

			$this->guzzle        = $guzzle;
			$this->httpTransport = $transport;
		}
	}


	/**
	 * @throws \RuntimeException
	 */
	private function createHttpUploadHandler()
	{
		if(!$this->uploadHandler && $this->guzzle) {
			$this->uploadHandler = new GuzzleUploadHandler($this->guzzle);
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