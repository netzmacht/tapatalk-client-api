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

use Netzmacht\Tapatalk\Result;


/**
 * Class SearchResult
 * @package Netzmacht\Tapatalk\Api\Search
 */
class SearchResult extends Result
{
	/**
	 * @var int
	 */
	private $searchId;

	/**
	 * @param array $items
	 * @param null $total
	 * @param int $offset
	 * @param null $searchId
	 */
	function __construct(array $items, $total = null, $offset = 0, $searchId=null)
	{
		parent::__construct($items, $total, $offset);

		$this->searchId = $searchId;
	}


	/**
	 * @return int
	 */
	public function getSearchId()
	{
		return $this->searchId;
	}

} 