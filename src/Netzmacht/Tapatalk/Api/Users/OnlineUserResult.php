<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Users;

use Netzmacht\Tapatalk\Result;

class OnlineUserResult extends Result
{
	/**
	 * @var int
	 */
	private $guestsNumber;

	/**
	 * @param array $items
	 * @param $total
	 * @param $offset
	 * @param $guests
	 */
	function __construct(array $items, $total, $offset, $guests)
	{
		parent::__construct($items, $total, $offset);

		$this->guestsNumber = $guests;
	}


	/**
	 * @return int
	 */
	public function getGuestsNumber()
	{
		return $this->guestsNumber;
	}

	/**
	 * @return int
	 */
	public function getMembersNumber()
	{
		return $this->getTotal();
	}

	/**
	 * @return int
	 */
	public function getAllUsersNumber()
	{
		return $this->guestsNumber + $this->getTotal();
	}

} 