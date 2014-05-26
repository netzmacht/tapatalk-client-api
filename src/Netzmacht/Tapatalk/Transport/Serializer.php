<?php

namespace Netzmacht\Tapatalk\Transport;


interface Serializer
{

	/**
	 * @param $value
	 * @return mixed
	 */
	public function serialize($value);


	/**
	 * @param $value
	 * @return mixed
	 */
	public function deserialize($value);

} 