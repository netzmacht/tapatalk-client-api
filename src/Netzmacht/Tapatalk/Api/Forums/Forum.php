<?php

namespace Netzmacht\Tapatalk\Api\Forums;


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Forum
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $logo;

	/**
	 * @var bool
	 */
	private $hasNewPost;

	/**
	 * @var bool
	 */
	private $isProtected;

	/**
	 * @param $id
	 * @param $name
	 * @param $logo
	 * @param $isProtected
	 * @param $hasNewPost
	 */
	function __construct($id, $name, $logo, $isProtected, $hasNewPost)
	{
		$this->id          = $id;
		$this->name        = $name;
		$this->logo        = $logo;
		$this->isProtected = $isProtected;
		$this->hasNewPost  = $hasNewPost;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('forum_id'),
			$response->get('forum_name', true),
			$response->get('logo_url'),
			$response->get('is_protected'),
			$response->get('new_post')
		);
	}


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @return boolean
	 */
	public function isProtected()
	{
		return $this->isProtected;
	}


	/**
	 * @return boolean
	 */
	public function hasNewPost()
	{
		return $this->hasNewPost;
	}


	/**
	 * @return string
	 */
	public function getLogo()
	{
		return $this->logo;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

} 