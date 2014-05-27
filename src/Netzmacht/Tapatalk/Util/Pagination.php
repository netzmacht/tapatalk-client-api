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
		return (int)($offset / $limit) + 1;
	}


	/**
	 * @param $limit
	 * @param $page
	 * @return int
	 */
	public function getOffset($limit, $page = 1)
	{
		if($page < 1) {
			$page = 1;
		}

		return (($page - 1) * $limit);
	}
}
