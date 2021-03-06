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
use Netzmacht\Tapatalk\Transport;

class TopicPost extends TopicBase
{

	/**
	 * @param MethodCallResponse $response
	 * @return Topic
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
			$response->get('forum_id'),
			$response->get('topic_title', true),
			$response->get('short_content', true),
			$author,
			DateTime::createFromTimestamp($response->get('last_reply_time') ? : $response->get('timestamp')),
			$response->get('new_post', false, false),
			$response->get('prefix', true),
			$response->get('is_subscribed', false, false),
			$response->get('is_closed', false, false),
			$response->get('reply_number'),
			$response->get('view_number'),
			$response->get('can_subscribe', false, true),
			$response->get('participated_uids', false, array())
		);
	}

	/**
	 * @return User
	 */
	public function getPostAuthor()
	{
		return $this->author;
	}

} 