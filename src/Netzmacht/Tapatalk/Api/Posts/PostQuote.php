<?php

namespace Netzmacht\Tapatalk\Api\Posts;


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class PostQuote
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $content;


	/**
	 * @param $id
	 * @param $title
	 * @param $content
	 */
	function __construct($id, $title, $content)
	{
		$this->id      = $id;
		$this->title   = $title;
		$this->content = $content;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('post_id'),
			$response->get('post_title', true),
			$response->get('post_content', true)
		);
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
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
	public function getTitle()
	{
		return $this->title;
	}

} 