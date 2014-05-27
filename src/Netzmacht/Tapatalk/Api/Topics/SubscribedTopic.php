<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Topics;


use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\DateTime;


class SubscribedTopic
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $forumId;

	/**
	 * @var string
	 */
	private $forumName;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var User
	 */
	private $author;

	/**
	 * @var int
	 */
	private $replyNumber;

	/**
	 * @var int
	 */
	private $viewNumber;

	/**
	 * @var \DateTime
	 */
	private $updatedAt;

	/**
	 * @var bool
	 */
	private $isClosed;


	/**
	 * @param $id
	 * @param $title
	 * @param $author
	 * @param $forumId
	 * @param $forumName
	 * @param $replyNumber
	 * @param $viewNumber
	 * @param $updatedAt
	 * @param $isClosed
	 */
	function __construct($id, $title, $author, $forumId, $forumName, $replyNumber, $viewNumber, $updatedAt, $isClosed)
	{
		$this->id          = $id;
		$this->title       = $title;
		$this->author      = $author;
		$this->forumId     = $forumId;
		$this->forumName   = $forumName;
		$this->replyNumber = $replyNumber;
		$this->viewNumber  = $viewNumber;
		$this->updatedAt   = $updatedAt;
		$this->isClosed    = $isClosed;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return SubscribedTopic
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		$author = new User(
			$response->get('post_author_id'),
			$response->get('post_author_name', true),
			$response->get('icon_url')
		);

		return new static(
			$response->get('topic_id'),
			$response->get('topic_title', true),
			$author,
			$response->get('forum_id'),
			$response->get('forum_name', true),
			$response->get('reply_number'),
			$response->get('view_number'),
			DateTime::createFromTimestamp($response->get('post_time') ?: $response->get('timestamp')),
			$response->get('is_closed', false, false)
		);
	}

	/**
	 * @return User
	 */
	public function getPostAuthor()
	{
		return $this->author;
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
	public function getForumName()
	{
		return $this->forumName;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getReplyNumber()
	{
		return $this->replyNumber;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	/**
	 * @return int
	 */
	public function getViewNumber()
	{
		return $this->viewNumber;
	}


} 