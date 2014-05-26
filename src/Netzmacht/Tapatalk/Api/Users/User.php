<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 23.05.14
 * Time: 07:33
 */

namespace Netzmacht\Tapatalk\Api\Users;


use Netzmacht\Tapatalk\Client;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class User
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $avatarUrl;


	/**
	 * @param int $id
	 * @param string $username
	 * @param string $avatar
	 */
	function __construct($id, $username, $avatar)
	{
		$this->id        = $id;
		$this->username  = $username;
		$this->avatarUrl = $avatar;
	}

	/**
	 * @param MethodCallResponse $response
	 * @return User
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('user_id'),
			$response->get('username', true),
			$response->get('icon_url')
		);
	}


	/**
	 * @return mixed
	 */
	public function getAvatarUrl()
	{
		return $this->avatarUrl;
	}


	/**
	 * @return bool
	 */
	public function hasAvatar()
	{
		return ($this->avatarUrl != null);
	}


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

} 