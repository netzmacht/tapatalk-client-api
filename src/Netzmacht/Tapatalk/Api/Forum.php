<?php

namespace Netzmacht\Tapatalk\Api;


use Netzmacht\Tapatalk\Api\Exception\InvalidResponseException;
use Netzmacht\Tapatalk\Client;

class Forum
{
	const MODE_ANNOUNCEMENT = 'ANN';
	const MODE_STICKY       = 'TOP';

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var int
	 */
	private $parentId;

	/**
	 * @var string
	 */
	private $logoUrl;

	/**
	 * @var bool
	 */
	private $hasNewPosts;

	/**
	 * @var bool
	 */
	private $isProtected;

	/**
	 * @var bool
	 */
	private $isSubscribed;


	/**
	 * @param $client
	 * @param $id
	 */
	function __construct($client, $id)
	{
		$this->client = $client;
		$this->id     = $id;
	}


	/**
	 * @param $subject
	 * @param $body
	 * @param null $prefixId
	 * @param Attachments $attachments
	 */
	public function createTopic($subject, $body, $prefixId=null, Attachments $attachments=null)
	{

	}


	/**
	 * @param int $limit
	 * @param int $offset
	 * @param null $mode
	 * @return array
	 * @throws Exception\InvalidResponseException
	 */
	public function getTopics($limit=50, $offset=0, $mode=null)
	{
		$topics = array();
		$params = array(
			'forum_id'  => (string) $this->id,
			'start_num' => $offset,
			'last_num'  => $limit+$offset-1,
		);

		if($mode) {
			$params['mode'] = $mode;
		}

		$response = $this->client->call('get_topic', $params);

		if(!isset($response['topics'])) {
			throw new InvalidResponseException('Invalid getTopics repsonse');
		}

		foreach($response['topics'] as $topic) {
			$topics[] = Topic::fromResponse($topic);
		}

		return $topics;
	}

} 