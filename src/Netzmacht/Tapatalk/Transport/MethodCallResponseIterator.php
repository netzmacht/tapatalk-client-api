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