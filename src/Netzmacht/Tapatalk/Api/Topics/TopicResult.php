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

use Netzmacht\Tapatalk\Result;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class TopicResult extends Result
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
	private $unreadStickyNumber;

	/**
	 * @var int
	 */
	private $unreadAnnouncementNumber;

	/**
	 * @var bool
	 */
	private $userCanPost;

	/**
	 * @var bool
	 */
	private $userCanSubscribe;

	/**
	 * @var bool
	 */
	private $isSubscribed;

	/**
	 * @var bool
	 */
	private $isPrefixRequired;

	/**
	 * @var array
	 */
	private $prefixes;


	/**
	 * @param array $items
	 * @param $total
	 * @param int $offset
	 * @param $forumId
	 * @param $forumName
	 * @param $isPrefixRequired
	 * @param $isSubscribed
	 * @param $prefixes
	 * @param $unreadAnnouncementNumber
	 * @param $unreadStickyNumber
	 * @param $userCanPost
	 * @param $userCanSubscribe
	 */
	function __construct(
		array $items,
		$total,
		$offset = 0,
		$forumId,
		$forumName,
		$isPrefixRequired,
		$isSubscribed,
		$prefixes,
		$unreadAnnouncementNumber,
		$unreadStickyNumber,
		$userCanPost,
		$userCanSubscribe
	)
	{
		parent::__construct($items, $total, $offset);

		$this->forumId                  = $forumId;
		$this->forumName                = $forumName;
		$this->isPrefixRequired         = $isPrefixRequired;
		$this->isSubscribed             = $isSubscribed;
		$this->prefixes                 = $prefixes;
		$this->unreadAnnouncementNumber = $unreadAnnouncementNumber;
		$this->unreadStickyNumber       = $unreadStickyNumber;
		$this->userCanPost              = $userCanPost;
		$this->userCanSubscribe         = $userCanSubscribe;
	}


	/**
	 * @param MethodCallResponse $response
	 * @param $offset
	 * @return TopicResult|Topic[]
	 */
	public static function fromResponse(MethodCallResponse $response, $offset)
	{
		return new static(
			static::buildTopics($response),
			$response->get('total_topic_num'),
			$offset,
			$response->get('forum_id'),
			$response->get('forum_name', true),
			$response->get('require_prefix', false, false),
			$response->get('is_subscribed', false, false),
			static::buildPrefixes($response),
			$response->get('unread_announce_count'),
			$response->get('unread_sticky_count'),
			$response->get('can_post', false, true),
			$response->get('can_subscribe', false, true)
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
	 * @return string
	 */
	public function getForumName()
	{
		return $this->forumName;
	}

	/**
	 * @return boolean
	 */
	public function isPrefixRequired()
	{
		return $this->isPrefixRequired;
	}

	/**
	 * @return boolean
	 */
	public function isSubscribed()
	{
		return $this->isSubscribed;
	}


	/**
	 * @return array
	 */
	public function getPrefixes()
	{
		return $this->prefixes;
	}


	/**
	 * @return int
	 */
	public function getUnreadAnnouncementNumber()
	{
		return $this->unreadAnnouncementNumber;
	}

	/**
	 * @return int
	 */
	public function getUnreadStickyNumber()
	{
		return $this->unreadStickyNumber;
	}


	/**
	 * @return boolean
	 */
	public function canPost()
	{
		return $this->userCanPost;
	}


	/**
	 * @return boolean
	 */
	public function canSubscribe()
	{
		return $this->userCanSubscribe;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return Topic[]
	 */
	private static function buildTopics(MethodCallResponse $response)
	{
		$topics = array();

		foreach($response->get('topics') as $topic) {
			$topics[] = Topic::fromResponse($topic);
		}

		return $topics;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return array
	 */
	private static function buildPrefixes(MethodCallResponse $response)
	{
		$prefixes = array();

		foreach((array)$response->get('prefixes') as $prefix) {
			$id    = $prefix->get('prefix_id', true);
			$label = $prefix->get('prefix_display_name', true);

			$prefixes[$id] = $label;
		}

		return $prefixes;
	}

} 