<?php

namespace Netzmacht\Tapatalk\Util;


class DateTime
{
	/**
	 * @param null $timestamp
	 * @return int|null
	 */
	public static function createFromTimestamp($timestamp=null)
	{
		if($timestamp === null) {
			$timestamp = time();
		}

		$dateTime = new \DateTime();
		$dateTime->setTimestamp($timestamp);

		return $dateTime;
	}
}