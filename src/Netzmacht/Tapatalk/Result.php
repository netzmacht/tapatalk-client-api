<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk;

use Symfony\Component\Validator\Constraints\All;
use Traversable;

class Result implements \IteratorAggregate
{
	/**
	 * @var array
	 */
	private $items;

	/**
	 * @var int
	 */
	private $total;

	/**
	 * @var int
	 */
	private $offset;


	/**
	 * @param array $items
	 * @param int $total
	 * @param int $offset
	 */
	function __construct(array $items, $total=null, $offset=0)
	{
		$this->items  = $items;
		$this->total  = $total === null ? count($items) : $total;
		$this->offset = $offset;
	}


	/**
	 * @return int
	 */
	public function getOffset()
	{
		return $this->offset;
	}


	/**
	 * @return int
	 */
	public function getTotal()
	{
		return $this->total;
	}


	/**
	 * Check if there are more results to fetch
	 *
	 * @return bool
	 */
	public function hasMore()
	{
		return ($this->offset + count($this->items) < $this->total);
	}


	/**
	 * Consider if result contains all provided items
	 */
	public function isAll()
	{
		return ($this->total == count($this->items));
	}


	/**
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}


	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->items);
	}

} 