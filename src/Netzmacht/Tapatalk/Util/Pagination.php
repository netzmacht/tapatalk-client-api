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


class Pagination
{

	/**
	 * @param $limit
	 * @param $offset
	 * @return int
	 */
	public static function getPage($limit, $offset)
	{
		return abs($offset/$limit)+1;
	}

} 