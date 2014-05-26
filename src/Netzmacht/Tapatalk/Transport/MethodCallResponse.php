<?php

namespace Netzmacht\Tapatalk\Transport;

use IteratorAggregate;



/**
 * Interface Response
 * @package Netzmacht\Tapatalk\Transport
 */
class MethodCallResponse implements IteratorAggregate
{

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var Serializer
	 */
	private $serializer;


	/**
	 * @param Serializer $serializer
	 * @param array $data
	 */
	function __construct(Serializer $serializer, array $data)
	{
		$this->serializer = $serializer;
		$this->data       = $data;
	}


	/**
	 * Get a response value
	 *
	 * @param string $name
	 * @param null $default
	 * @param bool $deserialize should value be deserialized
	 * @return mixed|MethodCallResponse|MethodCallResponse[]
	 */
	public function get($name, $deserialize=false, $default=null)
	{
		if(!array_key_exists($name, $this->data)) {
			return $default;
		}

		$value = $this->data[$name];

		if($deserialize) {
			$value = $this->serializer->deserialize($value);
		}

		if(is_array($value)) {
			return new static($this->serializer, $value);
		}

		return $value;
	}


	/**
	 * @param $name
	 * @return bool
	 */
	public function has($name)
	{
		return array_key_exists($name, $this->data);
	}


	/**
	 * Get all properties as array. If deserialized is true. it will convert all serialized values
	 *
	 * @param bool $deserialized
	 * @return array
	 */
	public function getData($deserialized=false)
	{
		if(!$deserialized) {
			return $this->data;
		}

		$data = $this->data;

		foreach($data as $name => $value) {
			if(is_array($value)) {
				$value = $this->get($name);
				$value = $value->getData($deserialized);
			}
			else {
				$value = $this->serializer->deserialize($value);
			}

			$data[$name] = $value;
		}

		return $data;
	}


	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return MethodCallResponseIterator|MethodCallResponse[]
	 * <b>Traversable</b>
	 */
	public function getIterator()
	{
		return new MethodCallResponseIterator($this->serializer, $this->data);
	}

}