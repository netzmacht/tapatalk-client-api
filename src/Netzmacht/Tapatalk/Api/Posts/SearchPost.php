<?php

namespace Netzmacht\Tapatalk\Api\Posts;


use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

/**
 * Class SearchPost
 * @package Netzmacht\Tapatalk\Api\Posts
 */
class SearchPost
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
	 * @var int
	 */
	private $topicId;

	/**
	 * @var string
	 */
	private $topicName;

	/**
	 * @var int
	 */
	private $forumId;

	/**
	 * @var string
	 */
	private $forumTitle;

	/**
	 * @var User
	 */
	private $author;

	/**
	 * @var string
	 */
	private $content;


	/**
	 * @param $id
	 * @param $title
	 * @param $content
	 * @param $author
	 * @param $forumId
	 * @param $forumTitle
	 * @param $topicId
	 * @param $topicName
	 */
	function __construct($id, $title, $content, $author, $forumId, $forumTitle, $topicId, $topicName)
	{
		$this->author     = $author;
		$this->content    = $content;
		$this->forumId    = $forumId;
		$this->forumTitle = $forumTitle;
		$this->id         = $id;
		$this->title      = $title;
		$this->topicId    = $topicId;
		$this->topicName  = $topicName;
	}


	/**
	 * @param MethodCallResponse $response
	 * @internal param $post
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('post_id'),
			$response->get('post_title', true),
			$response->get('short_content', true),
			PostAuthor::fromResponse($response),
			$response->get('forum_id'),
			$response->get('forum_name', true),
			$response->get('topic_id'),
			$response->get('topic_title', true)
		);
	}

	/**
	 * @return \Netzmacht\Tapatalk\Api\Users\User
	 */
	public function getAuthor()
	{
		return $this->author;
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
	public function getForumId()
	{
		return $this->forumId;
	}

	/**
	 * @return string
	 */
	public function getForumTitle()
	{
		return $this->forumTitle;
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

	/**
	 * @return int
	 */
	public function getTopicId()
	{
		return $this->topicId;
	}

	/**
	 * @return string
	 */
	public function getTopicName()
	{
		return $this->topicName;
	}

}