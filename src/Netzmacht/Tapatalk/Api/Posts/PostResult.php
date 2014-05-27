<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Posts;

use Netzmacht\Tapatalk\Result;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class PostResult extends Result
{
	/**
	 * @var int
	 */
	private $forumId;

	/**
	 * @var string
	 */
	private $forumName;

	/**
	 * @var int
	 */
	private $topicId;

	/**
	 * @var string
	 */
	private $topicTitle;

	/**
	 * @var string
	 */
	private $prefix;

	/**
	 * @var bool
	 */
	private $isSubscribed;

	/**
	 * @var bool
	 */
	private $canSubscribe;

	/**
	 * @var bool
	 */
	private $isPoll;

	/**
	 * @var bool
	 */
	private $isClosed;

	/**
	 * @var bool
	 */
	private $canUserReport;

	/**
	 * @var bool
	 */
	private $canUserReply;

	/**
	 * @var BreadcrumbNode[]
	 */
	private $breadcrumb;

	/**
	 * @var PostFull[]
	 */
	private $posts;


	/**
	 * @param array $forumId
	 * @param int|null $forumName
	 * @param int $topicId
	 * @param $topicTitle
	 * @param $prefix
	 * @param $breadcrumb
	 * @param $posts
	 * @param $isPoll
	 * @param $isClosed
	 * @param $isSubscribed
	 * @param $canSubscribe
	 * @param $canUserReply
	 * @param $canUserReport
	 */
	function __construct(
		$forumId,
		$forumName,
		$topicId,
		$topicTitle,
		$prefix,
		$breadcrumb,
		$posts,
		$isPoll,
		$isClosed,
		$isSubscribed,
		$canSubscribe,
		$canUserReply,
		$canUserReport
	) {
		$this->forumId       = $forumId;
		$this->forumName     = $forumName;
		$this->topicId       = $topicId;
		$this->topicTitle    = $topicTitle;
		$this->prefix        = $prefix;
		$this->breadcrumb    = $breadcrumb;
		$this->posts         = $posts;
		$this->isPoll        = $isPoll;
		$this->isClosed      = $isClosed;
		$this->isSubscribed  = $isSubscribed;
		$this->canSubscribe  = $canSubscribe;
		$this->canUserReply  = $canUserReply;
		$this->canUserReport = $canUserReport;
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
			$response->get('topic_id'),
			$response->get('topic_title', true),
			$response->get('prefix', true),
			static::createBreadcrumb($response),
			static::createPosts($response),
			$response->get('is_poll'),
			$response->get('is_closed'),
			$response->get('is_subscribed'),
			$response->get('can_subscribe'),
			$response->get('can_reply'),
			$response->get('can_report')
		);
	}


	/**
	 * @param MethodCallResponse $response
	 * @return BreadcrumbNode[]
	 */
	private static function createBreadcrumb(MethodCallResponse $response)
	{
		$breadcrumb = array();

		foreach($response->get('breadcrumb', false, array()) as $node) {
			$breadcrumb[] = BreadcrumbNode::fromResponse($node);
		}

		return $breadcrumb;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return PostFull[]
	 */
	private static function createPosts(MethodCallResponse $response)
	{
		$posts = array();

		foreach($response->get('posts', false, array()) as $post) {
			$posts[] = PostFull::fromResponse($post);
		}

		return $posts;
	}

	/**
	 * @return \Netzmacht\Tapatalk\Api\Posts\BreadcrumbNode[]
	 */
	public function getBreadcrumb()
	{
		return $this->breadcrumb;
	}

	/**
	 * @return boolean
	 */
	public function canUserSubscribe()
	{
		return $this->canSubscribe;
	}

	/**
	 * @return boolean
	 */
	public function canUserReply()
	{
		return $this->canUserReply;
	}

	/**
	 * @return boolean
	 */
	public function canUserReport()
	{
		return $this->canUserReport;
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
	 * @return boolean
	 */
	public function isClosed()
	{
		return $this->isClosed;
	}

	/**
	 * @return boolean
	 */
	public function isPoll()
	{
		return $this->isPoll;
	}

	/**
	 * @return boolean
	 */
	public function isSubscribed()
	{
		return $this->isSubscribed;
	}

	/**
	 * @return \Netzmacht\Tapatalk\Api\Posts\PostFull[]
	 */
	public function getPosts()
	{
		return $this->posts;
	}

	/**
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
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
	public function getTopicTitle()
	{
		return $this->topicTitle;
	}

} 