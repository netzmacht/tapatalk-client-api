<?php

namespace Netzmacht\Tapatalk\Api\Posts;


use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Client;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class PostAuthor extends User
{
	/**
	 * @var bool
	 */
	private $isOnline;


	/**
	 * @param int $id
	 * @param string $username
	 * @param string $avatar
	 * @param $isOnline
	 */
	function __construct($id, $username, $avatar, $isOnline)
	{
		parent::__construct($id, $username, $avatar);

		$this->isOnline = $isOnline;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return User
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('post_author_id'),
			$response->get('post_author_name', true),
			$response->get('icon_url'),
			$response->get('is_online')
		);
	}


	/**
	 * @return boolean
	 */
	public function IsOnline()
	{
		return $this->isOnline;
	}

} 