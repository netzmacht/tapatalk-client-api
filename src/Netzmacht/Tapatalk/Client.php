<?php

namespace Netzmacht\Tapatalk;

use Netzmacht\Tapatalk\Api\Account;
use Netzmacht\Tapatalk\Api\Config;
use Netzmacht\Tapatalk\Api\Forums;
use Netzmacht\Tapatalk\Api\Posts;
use Netzmacht\Tapatalk\Api\Users;
use Netzmacht\Tapatalk\Api\Topics;

/**
 * Class Client
 * @package Netzmacht\Tapatalk
 */
class Client extends Api
{

	/**
	 * @var Account
	 */
	private $account;


	/**
	 * @param Transport $transport
	 * @param string|null $username
	 * @param string|null $password
	 */
	function __construct(Transport $transport, $username=null, $password=null)
	{
		parent::__construct($transport, $this->loadConfig($transport));

		$this->account = new Account($this->transport, $this->config);

		if($username) {
			$this->account()->login($username, $password);
		}
	}


	/**
	 * User account api
	 *
	 * @return Account
	 */
	public function account()
	{
		return $this->account;
	}


	/**
	 * Get Users API to run user specific api calls
	 *
	 * @return Users
	 */
	public function users()
	{
		return new Users($this->transport, $this->config);
	}


	public function forums()
	{
		return new Forums($this->transport, $this->config);
	}


	public function topics()
	{
		return new Topics($this->transport, $this->config);
	}


	/**
	 * @return Posts
	 */
	public function posts()
	{
		return new Posts($this->transport, $this->config);
	}


	public function attachments()
	{

	}


	/**
	 * Get Forum config
	 *
	 * @return Config
	 */
	public function config()
	{
		return $this->config;
	}


	/**
	 * Load config
	 * @param Transport $transport pass transport as param. it is not yet as object property when called
	 * @return Config
	 */
	private function loadConfig(Transport $transport)
	{
		$response = $transport->call('get_config', array(), false);

		$this->assert()->noResultState($response);

		return Config::fromResponse($response);
	}

}
