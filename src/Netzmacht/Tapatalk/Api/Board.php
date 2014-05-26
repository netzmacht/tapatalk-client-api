<?php

namespace Netzmacht\Tapatalk\Api;


use Netzmacht\Tapatalk\Api;
use Netzmacht\Tapatalk\Api\Account\Alert;
use Netzmacht\Tapatalk\Api\Board\Ids;
use Netzmacht\Tapatalk\Api\Board\Smilies;
use Netzmacht\Tapatalk\Api\Board\Statistics;
use Netzmacht\Tapatalk\Api\Config\Features;
use Netzmacht\Tapatalk\Result;
use Netzmacht\Tapatalk\Util\Pagination;

class Board extends Api
{
	/**
	 * Get ids from given forum url
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=1#get_id_by_url
	 * @param $url
	 * @return Ids
	 */
	public function getIdByUrl($url)
	{
		$response = $this->transport->call('get_id_by_url', array('url' => $url));
		$this->assert()->noResultState($response);

		return Ids::fromResponse($response);
	}


	/**
	 * Get board statistics
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=1#get_board_stat
	 * @return Statistics
	 */
	public function getStatistics()
	{
		$response = $this->transport->call('get_board_stat');
		$this->assert()->noResultState($response);

		return Statistics::fromResponse($response);
	}

	/**
	 * Get all smilies
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=1#get_smilies
	 * @return Smilies
	 */
	public function getSmilies()
	{
		$response = $this->transport->call('get_smilies');
		$this->assert()->noResultState($response);

		return Smilies::fromResponse($response);
	}


	/**
	 * Get all activities happened
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=9#get_activity
	 * @param int $limit
	 * @param int $offset
	 * @return Result
	 */
	public function getActivity($limit=20, $offset=0)
	{
		$this->assert()->featureSupported(Features::GET_ACTIVITY);

		$params = array(
			'page'    => Pagination::getPage($limit, $offset),
			'perpage' => $limit
		);

		$response = $this->transport->call('get_activity', $params);
		$this->assert()->noResultState($response);

		$alerts = array();

		foreach($response->get('items') as $alert) {
			$alerts[] = Alert::fromResponse($alert);
		}

		return new Result($alerts, $response->get('total'), $offset);
	}

} 