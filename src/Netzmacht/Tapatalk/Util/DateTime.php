<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

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