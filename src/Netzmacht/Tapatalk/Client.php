<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */


namespace Netzmacht\Tapatalk;

use Netzmacht\Tapatalk\Api\Account;
use Netzmacht\Tapatalk\Api\Attachments;
use Netzmacht\Tapatalk\Api\Board;
use Netzmacht\Tapatalk\Api\Config;
use Netzmacht\Tapatalk\Api\Exception\ApiServiceNotAvailableException;
use Netzmacht\Tapatalk\Api\Exception\ApiVersionNotSupportedException;
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

		$this->checkRequirements();

		$this->account = new Account($this->transport, $this->config);

		if($username) {
			$this->account()->login($username, $password);
		}
	}


	/**
	 * @return Board
	 */
	public function board()
	{
		return new Board($this->transport, $this->config);
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

	/**
	 * @return Attachments
	 * @throws
	 */
	public function attachments()
	{
		// TODO: Implement
		trigger_error('Not implemented: ' . __METHOD__, E_USER_ERROR);
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


	/**
	 * Private Messages API
	 *
	 * Not implemented yet
	 *
	 * @return Messages
	 * @throws
	 */
	public function messages()
    {
	    // TODO: Implement
	    trigger_error('Not implemented: ' . __METHOD__, E_USER_ERROR);
    }


	/**
	 * Private Conversations API
	 *
	 * Not implemented yet
	 *
	 * @return Conversations
	 * @throws
	 */
    public function conversations()
    {
	    // TODO: Implement
	    trigger_error('Not implemented: ' . __METHOD__, E_USER_ERROR);
    }


	/**
	 * Moderation API
	 *
	 * Not implemented yet
	 *
	 * @return Moderation
	 * @throws
	 */
    public function moderation()
    {
	    // TODO: Implement
	    trigger_error('Not implemented: ' . __METHOD__, E_USER_ERROR);
    }


	/**
	 * Check requirements of
	 */
	private function checkRequirements()
	{
		// api version requirements
		if(version_compare($this->config()->getApiVersion(), '4', '<')) {
			throw new ApiVersionNotSupportedException(
				sprintf('Api version "%s" is not supported', $this->config()->getApiVersion()),
				$this->config()->getApiVersion()
			);
		}

		// forum has to be open
		if(!$this->config()->isOpen()) {
			throw new ApiServiceNotAvailableException('Api service is not available.');
		}
	}

}
