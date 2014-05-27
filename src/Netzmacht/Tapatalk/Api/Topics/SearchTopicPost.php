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


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class SearchTopicPost extends TopicPost
{
	/**
	 * @var string
	 */
	private $forumName;

	/**
	 * @param MethodCallResponse $response
	 * @return Topic
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		$topic = parent::fromResponse($response);
		$topic->forumName = $response->get('forum_name', true);

		return $topic;
	}

	/**
	 * @return string
	 */
	public function getForumName()
	{
		return $this->forumName;
	}

} 