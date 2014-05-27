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

/**
 * Class UnreadTopicResult
 * @package Netzmacht\Tapatalk\Api\Topics
 */
class TopicPostResult extends Result
{
	/**
	 * @var int
	 */
	private $searchId;


	/**
	 * @param array $items
	 * @param int $total
	 * @param int $searchId
	 * @param int $offset
	 */
	function __construct(array $items, $total, $searchId, $offset = 0)
	{
		parent::__construct($items, $total, $offset);

		$this->searchId = $searchId;
	}


	/**
	 * @param MethodCallResponse $response
	 * @param $offset
	 * @return TopicPostResult
	 */
	public static function fromResponse(MethodCallResponse $response, $offset)
	{
		$topics = array();

		foreach($response->get('topics') as $topic) {
			$topics[] = TopicPost::fromResponse($topic);
		}

		return new static(
			$topics,
			$response->get('total_topic_num'),
			$response->get('search_id'),
			$offset
		);
	}


	/**
	 * @return int
	 */
	public function getSearchId()
	{
		return $this->searchId;
	}

}
