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
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

/**
 * Class UnreadPostResult
 * @package Netzmacht\Tapatalk\Api\Posts
 */
class PositionedPostResult extends PostResult
{
	/**
	 * @var int
	 */
	private $position;

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
	 * @param null $position
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
		$canUserReport,
		$position=null
	) {
		parent::__construct(
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
		);

		$this->position = $position;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return PositionedPostResult|static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		/** @var PositionedPostResult $result */
		$result = parent::fromResponse($response);
		$result->position = $response->get('position');

		return $result;
	}

	/**
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

} 