<?php

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