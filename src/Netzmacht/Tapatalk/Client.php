<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

/**
 * Copyright 2014 netzmacht creative David Molineus
 *
 * Licensed under the GNU Lesser General Public_License (LGPL), Version 3.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.gnu.org/licenses/lgpl-3.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */


namespace Netzmacht\Tapatalk;

use Netzmacht\Tapatalk\Api\Account;
use Netzmacht\Tapatalk\Api\Board;
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
