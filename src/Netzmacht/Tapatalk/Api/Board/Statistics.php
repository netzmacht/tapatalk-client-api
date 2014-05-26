<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 26.05.14
 * Time: 17:52
 */

namespace Netzmacht\Tapatalk\Api\Board;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;


/**
 * Class Statistics
 * @package Netzmacht\Tapatalk\Api\Board
 */
class Statistics
{
	/**
	 * @var int
	 */
	private $threadsNumber;

	/**
	 * @var int
	 */
	private $postsNumber;

	/**
	 * @var int
	 */
	private $membersNumber;

	/**
	 * @var int
	 */
	private $activeMembersNumber;

	/**
	 * @var int
	 */
	private $onlineUsersNumber;

	/**
	 * @var int
	 */
	private $onlineGuestNumber;

	/**
	 * @param $activeMembersNumber
	 * @param $membersNumber
	 * @param $onlineGuestNumber
	 * @param $onlineUsersNumber
	 * @param $postsNumber
	 * @param $threadsNumber
	 */
	function __construct($activeMembersNumber, $membersNumber, $onlineGuestNumber, $onlineUsersNumber, $postsNumber, $threadsNumber)
	{
		$this->activeMembersNumber = $activeMembersNumber;
		$this->membersNumber       = $membersNumber;
		$this->onlineGuestNumber   = $onlineGuestNumber;
		$this->onlineUsersNumber   = $onlineUsersNumber;
		$this->postsNumber         = $postsNumber;
		$this->threadsNumber       = $threadsNumber;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('active_members'),
			$response->get('total_members'),
			$response->get('guest_online'),
			$response->get('total_online'),
			$response->get('total_posts'),
			$response->get('total_threads')
		);
	}


	/**
	 * @return int
	 */
	public function getActiveMembersNumber()
	{
		return $this->activeMembersNumber;
	}


	/**
	 * @return int
	 */
	public function getMembersNumber()
	{
		return $this->membersNumber;
	}


	/**
	 * @return int
	 */
	public function getOnlineGuestNumber()
	{
		return $this->onlineGuestNumber;
	}


	/**
	 * @return int
	 */
	public function getOnlineUsersNumber()
	{
		return $this->onlineUsersNumber;
	}


	/**
	 * @return int
	 */
	public function getPostsNumber()
	{
		return $this->postsNumber;
	}


	/**
	 * @return int
	 */
	public function getThreadsNumber()
	{
		return $this->threadsNumber;
	}

} 