<?php

namespace Netzmacht\Tapatalk\Transport;


class MethodCallResponseIterator extends \ArrayIterator
{
	/**
	 * @var Serializer
	 */
	private $serializer;


	/**
	 * @param Serializer $serializer
	 * @param array $array
	 * @param int $flags
	 */
	public function __construct(Serializer $serializer, $array = array(), $flags = 0)
	{
		parent::__construct($array, $flags);

		$this->serializer = $serializer;
	}


	/**
	 * @return mixed|void|MethodCallResponse
	 */
	public function current()
	{
		$data = parent::current();

		if(is_array($data)) {
			$data = new MethodCallResponse($this->serializer, $data);
		}

		return $data;
	}

} 