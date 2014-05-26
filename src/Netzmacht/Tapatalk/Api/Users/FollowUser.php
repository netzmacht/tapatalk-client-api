<?php

namespace Netzmacht\Tapatalk\Api\Users;


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class FollowUser extends User
{
	/**
	 * @var string
	 */
	private $displayText;

	/**
	 * @var bool
	 */
	private $isOnline;

	/**
	 * @param int $id
	 * @param string $username
	 * @param string $displayText
	 * @param $isOnline
	 */
	function __construct($id, $username, $displayText, $isOnline)
	{
		parent::__construct($id, $username, null);

		$this->displayText = $displayText;
		$this->isOnline    = $isOnline;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return FollowUser
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('user_id'),
			$response->get('username', true),
			$response->get('display_text', true),
			$response->get('is_online')
		);
	}


	/**
	 * @return string
	 */
	public function getDisplayText()
	{
		return $this->displayText;
	}

	/**
	 * @return boolean
	 */
	public function isOnline()
	{
		return $this->isOnline;
	}



} 