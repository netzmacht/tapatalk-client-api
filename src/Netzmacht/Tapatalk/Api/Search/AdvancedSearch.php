<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Search;
use Netzmacht\Tapatalk\Transport\MethodCall;
use Netzmacht\Tapatalk\Transport\Serializer;
use Netzmacht\Tapatalk\Util\Pagination;

/**
 * Class AdvancedSearch
 * @package Netzmacht\Tapatalk\Api\Search
 */
class AdvancedSearch
{
	const SEARCH_ID  = 'searchid';

	const USERNAME   = 'searchuser';

	const KEYWORDS   = 'keywords';

	const USER_ID    = 'userid';

	const FORUM_ID   = 'froumid';

	const THREAD_ID  = 'threadid';

	const TITLE_ONLY = 'titleonly';

	const SEARCH_TIME = 'searchtime';

	const ONLY_IN     = 'only_in';

	const NOT_IN      = 'not_in';


	/**
	 * @return array
	 */
	public static function getFilters()
	{
		$reflector = new \ReflectionClass(get_called_class());
		return array_flip(array_values($reflector->getConstants()));
	}


	/**
	 * @param \Netzmacht\Tapatalk\Transport\MethodCall $method
	 * @param array $data
	 * @param int $limit
	 * @param int $offset
	 * @param null $searchId
	 * @internal param array $filters
	 * @return array
	 */
	public static function applyFilters(MethodCall $method, array $data, $limit=20, $offset=20, $searchId=null)
	{
		$filters = array_merge((array) $method->getParam('filters'), array(
			'page'      => Pagination::getPage($limit, $offset),
			'perpage'   => $limit
		));

		if($searchId) {
			$filters['searchid'] = $searchId;
		}
		else {
			$data       = array_intersect_key($data, static::getFilters());
			$serializer = $method->getSerializer();

			foreach($data as $name => $filter) {
				$filters[$name] = self::sanitizeValue($name, $filter, $serializer);
			}
		}

		$method->set('filters', $filters);

		return $filters;
	}


	/**
	 * @param $name
	 * @param Serializer $serializer
	 * @param $filter
	 * @return array|int|string
	 */
	private static function sanitizeValue($name, $filter, Serializer $serializer)
	{
		switch($name) {
			// serialize value
			case static::USERNAME:
			case static::KEYWORDS:
				$filter = $serializer->serialize($filter);
				break;

			// force string
			case static::SEARCH_ID:
			case static::THREAD_ID:
			case static::FORUM_ID:
				$filter = (string)$filter;
				break;

			// force string for array values
			case static::NOT_IN:
			case static::ONLY_IN:
				$filter = array_map('strval', $filter);
				break;

			// convert datetime to timestamp
			case static::SEARCH_TIME:
				if($filter instanceof \DateTime) {
					$filter = $filter->getTimestamp();
				}

				break;

			// boolean to int
			case static::TITLE_ONLY:
				$filter = $filter ? 1 : 0;
				break;
		}
		return $filter;
	}

} 