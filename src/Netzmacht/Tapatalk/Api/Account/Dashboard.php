<?php

namespace Netzmacht\Tapatalk\Api\Account;


use Netzmacht\Tapatalk\Api\Account\Dashboard\Alert;
use Netzmacht\Tapatalk\Api\Account\Dashboard\Feed;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Dashboard
{
	/**
	 * @var int
	 */
	private $totalLikesNumber;

	/**
	 * @var int
	 */
	private $newAlertsNumber;

	/**
	 * @var Feed[]
	 */
	private $likes;

	/**
	 * @var Alert[]
	 */
	private $alerts;

	/**
	 * @var Feed[]
	 */
	private $feeds;


	/**
	 * @param $alerts
	 * @param $feeds
	 * @param $likes
	 * @param $newAlertsNumber
	 * @param $totalLikesNumber
	 */
	function __construct($alerts, $feeds, $likes, $newAlertsNumber, $totalLikesNumber)
	{
		$this->alerts           = $alerts;
		$this->feeds            = $feeds;
		$this->likes            = $likes;
		$this->newAlertsNumber  = $newAlertsNumber;
		$this->totalLikesNumber = $totalLikesNumber;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		$feeds  = array();
		$alerts = array();
		$likes  = array();

		foreach($response->get('feeds') as $feed) {
			$feeds[] = Feed::fromResponse($feed);
		}

		foreach($response->get('alerts') as $alert) {
			$alerts[] = Alert::fromResponse($alert);
		}

		foreach($response->get('likes') as $like) {
			$likes[] = Feed::fromResponse($like);
		}

		return new static($alerts, $feeds, $likes, $response->get('new_alerts'), $response->get('total_likes'));
	}

} 