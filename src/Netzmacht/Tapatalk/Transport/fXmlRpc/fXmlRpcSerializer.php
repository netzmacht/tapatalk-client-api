<?php

namespace Netzmacht\Tapatalk\Transport\fXmlRpc;

use fXmlRpc\Value\Base64;
use fXmlRpc\Value\Base64Interface;
use Netzmacht\Tapatalk\Transport\Serializer;

class fXmlRpcSerializer implements Serializer
{

	/**
	 * @param $value
	 * @return mixed
	 */
	public function serialize($value)
	{
		if(!$value instanceof Base64Interface) {
			$value = Base64::serialize($value);
		}

		return $value;
	}


	/**
	 * @param $value
	 * @return mixed
	 */
	public function deserialize($value)
	{
		if($value instanceof Base64Interface) {
			return $value->getDecoded();
		}

		return $value;
	}

} 