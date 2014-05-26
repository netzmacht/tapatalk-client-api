<?php

namespace Netzmacht\Tapatalk;


use Netzmacht\Tapatalk\Transport\fXmlRpc\fxmlRpcTransportFactory;

class Factory
{
	/**
	 * @var transport factory
	 */
	private $transportFactory;

	/**
	 * @var string
	 */
	private $uri;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;


	/**
	 * @param $uri
	 * @param null $username
	 * @param null $password
	 * @return \Netzmacht\Tapatalk\Client
	 */
	public static function forBoard($uri, $username=null, $password=null)
	{
		/** @var Factory $factory */
		$factory = new Factory();

		return $factory
			->setCredentials($username, $password)
			->setUri($uri)
			->create();
	}


	/**
	 * @param mixed $transportFactory
	 * @return $this
	 */
	public function setTransportFactory($transportFactory)
	{
		$this->transportFactory = $transportFactory;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTransportFactory()
	{
		return $this->transportFactory;
	}

	/**
	 * @param $username
	 * @param $password
	 * @return $this
	 */
	public function setCredentials($username, $password)
	{
		$this->username = $username;
		$this->password = $password;

		return $this;
	}


	/**
	 * @param mixed $uri
	 * @return $this
	 */
	public function setUri($uri)
	{
		$this->uri = $uri;

		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getUri()
	{
		return $this->uri;
	}


	/**
	 * @return Client
	 */
	public function create()
	{
		$transport = $this->createTransport();
		$client    = new Client($transport, $this->username, $this->password);

		return $client;
	}


	/**
	 * @return Transport
	 */
	private function createTransport()
	{
		if(!$this->transportFactory) {
			$this->transportFactory = new fxmlRpcTransportFactory();
		}

		$this->transportFactory->setUri($this->uri);

		return $this->transportFactory->create();
	}

} 