<?php

namespace Netzmacht\Tapatalk\Api\Topics;
use Netzmacht\Tapatalk\Api\Users\User;


/**
 * Class TopicBase
 * @package Netzmacht\Tapatalk\Api\Topics
 */
abstract class TopicBase extends TopicStatus
{

	/**
	 * @var int
	 */
	private $forumId;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $prefix;

	/**
	 * @var User
	 */
	protected $author;

	/**
	 * @var string
	 */
	private $shortContent;

	/**
	 * @var array
	 */
	private $participatedUserIds;


	/**
	 * @param $id
	 * @param $forumId
	 * @param $title
	 * @param $shortContent
	 * @param $author
	 * @param $lastReplyAt
	 * @param $hasNewPosts
	 * @param $prefix
	 * @param $isSubscribed
	 * @param $isClosed
	 * @param $replyNumber
	 * @param $viewNumber
	 * @param $canSubscribe
	 * @param $participatedUserIds
	 */
	function __construct(
		$id,
		$forumId,
		$title,
		$shortContent,
		$author,
		$lastReplyAt,
		$hasNewPosts,
		$prefix,
		$isSubscribed,
		$isClosed,
		$replyNumber,
		$viewNumber,
		$canSubscribe,
		$participatedUserIds
	) {
		parent::__construct($id, $isClosed, $isSubscribed, $hasNewPosts, $canSubscribe, $lastReplyAt, $replyNumber, $viewNumber);

		$this->forumId             = $forumId;
		$this->title               = $title;
		$this->shortContent        = $shortContent;
		$this->author              = $author;
		$this->prefix              = $prefix;
		$this->participatedUserIds = $participatedUserIds;
	}


	/**
	 * @return int
	 */
	public function getForumId()
	{
		return $this->forumId;
	}


	/**
	 * @return array
	 */
	public function getParticipatedUserIds()
	{
		return $this->participatedUserIds;
	}


	/**
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}


	/**
	 * @return string
	 */
	public function getShortContent()
	{
		return $this->shortContent;
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

} 