<?php

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