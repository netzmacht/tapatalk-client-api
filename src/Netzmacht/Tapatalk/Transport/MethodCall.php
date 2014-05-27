<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Transport;
use Netzmacht\Tapatalk\Transport;

/**
 * Class Request
 * @package Netzmacht\Tapatalk\Transport
 */
class MethodCall
{
	/**
	 * @var
	 */
	private $method;

	/**
	 * @var
	 */
	private $params = array();

	/**
	 * @var Serializer
	 */
	private $serializer;

	/**
	 * @var Transport
	 */
	private $transport;


	/**
	 * @param \Netzmacht\Tapatalk\Transport $transport
	 * @param Serializer $serializer
	 * @param $method
	 * @param array $params
	 */
	function __construct(Transport $transport, Serializer $serializer, $method, array $params=array())
	{
		$this->transport  = $transport;
		$this->serializer = $serializer;
		$this->method     = $method;
		$this->params     = $params;
	}

	/**
	 * @return \Netzmacht\Tapatalk\Transport\Serializer
	 */
	public function getSerializer()
	{
		return $this->serializer;
	}


	/**
	 * @param array $params
	 * @return $this
	 */
	public function addParams(array $params)
	{
		foreach($params as $name => $value) {
			$this->set($name, $value);
		}

		return $this;
	}


	/**
	 * @param $name
	 * @param $value
	 * @param bool $serialize
	 * @return $this
	 */
	public function set($name, $value, $serialize=false)
	{
		if($serialize) {
			$value = $this->serializer->serialize($value);
		}

		$this->params[$name] = $value;

		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getMethod()
	{
		return $this->method;
	}


	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}


	/**
	 * @return MethodCallResponse
	 */
	public function call()
	{
		return $this->transport->call($this->method, $this->params);
	}


	/**
	 * @param $name
	 * @param null $default
	 * @return null|$default
	 */
	public function getParam($name, $default=null)
	{
		if(array_key_exists($name, $this->params)) {
			return $this->params[$name];

		}

		return $default;
	}

} 