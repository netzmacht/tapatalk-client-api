<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Transport\Guzzle;

use Guzzle\Http\Client;

/**
 * Class GuzzleUploadHandler
 * @package Netzmacht\Tapatalk\Transport
 */
class GuzzleUploadHandler
{
	/**
	 * @var Client
	 */
	private $client;


	/**
	 * @param Client $client
	 */
	function __construct(Client $client)
	{
		$this->client = $client;
	}


	/**
	 * @param $path
	 * @param $file
	 * @param array $params
	 * @return string
	 */
	public function upload($path, $file, $params = array())
	{
		$params = array_merge($params, array(
			'content' => pathinfo($file, PATHINFO_BASENAME),
			'file'    => fopen($file, 'r'),
		));

		$request = $this->client
			->post($path, $params)
			->setHeader('Content-Disposition', 'form-data; name="group_id');

		$response = $this->client->send($request);

		return (string)$response->getBody();
	}


} 