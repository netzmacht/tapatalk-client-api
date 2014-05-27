<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */


namespace Netzmacht\Tapatalk\Api\Board;


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Ids
{
	/**
	 * @var int
	 */
	private $forumId;

	/**
	 * @var int
	 */
	private $topicId;

	/**
	 * @var int
	 */
	private $postId;


	/**
	 * @param $forumId
	 * @param $topicId
	 * @param $postId
	 */
	function __construct($forumId, $topicId, $postId)
	{
		$this->forumId = $forumId;
		$this->topicId = $topicId;
		$this->postId  = $postId;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('forum_id'),
			$response->get('topic_id'),
			$response->get('post_id')
		);
	}


	/**
	 * @return int
	 */
	public function getForumId()
	{
		return $this->forumId;
	}

	/**
	 * @return int
	 */
	public function getPostId()
	{
		return $this->postId;
	}


	/**
	 * @return int
	 */
	public function getTopicId()
	{
		return $this->topicId;
	}


	/**
	 * @return int
	 */
	public function hasForumId()
	{
		return ($this->forumId !== null);
	}


	/**
	 * @return int
	 */
	public function hasPostId()
	{
		return ($this->postId !== null);
	}


	/**
	 * @return int
	 */
	public function hasTopicId()
	{
		return ($this->topicId !== null);
	}


} 