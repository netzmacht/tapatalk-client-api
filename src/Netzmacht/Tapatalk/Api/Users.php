<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api;

use Netzmacht\Tapatalk\Api\Users\FollowUser;
use Netzmacht\Tapatalk\Api\Users\OnlineUser;
use Netzmacht\Tapatalk\Api\Users\OnlineUserResult;
use Netzmacht\Tapatalk\Api\Users\RecommendedUser;
use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Api\Users\UserInfo;
use Netzmacht\Tapatalk\Api;
use Netzmacht\Tapatalk\Exception\NotImplementedException;
use Netzmacht\Tapatalk\Result;
use Netzmacht\Tapatalk\Transport;


class Users extends Api
{

	const USERNAME = 'user_name';
	const USER_ID  = 'user_id';

	const RECOMMENDED_ALL          = 1;
	const RECOMMENDED_NON_TAPATALK = 2;

	const REPUTATION_POSITIVE = 'ADD';
	const REPUTATION_NEGATIVE = 'SUBTRACT';


	/**
	 * Get detailed user info of given user. UserId can be the User ID or username.
	 * It it selected by §identifier.
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=2#get_user_info
	 * @param $userId
	 * @param string $identifier
	 * @return \Netzmacht\Tapatalk\Api\Users\UserInfo
	 */
	public function getUserInfo($userId, $identifier = Users::USER_ID)
	{
		$this->assertValidIdentifier($identifier);

		$response = $this->transport->createMethodCall('get_user_info')
			->set($identifier, $userId, $identifier == static::USERNAME)
			->call();

		return UserInfo::fromResponse($this->transport, $response);
	}


	/**
	 * List users being recommended to logged in user.
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=2#get_recommended_user
	 * @param int $mode
	 * @param int $limit
	 * @param int $offset
	 * @return Result|RecommendedUser[]
	 */
	public function getRecommendUsers($mode = Users::RECOMMENDED_ALL, $limit = 50, $offset = 0)
	{
		$this->assertValidRecommendMode($mode);

		$params   = $this->addPagination($limit, $offset, array('mode' => $mode));
		$response = $this->transport->call('get_recommended_user', $params);
		$users    = array();

		foreach($response->get('list') as $user) {
			$users[] = RecommendedUser::fromResponse($this->transport, $user);
		}

		return new Result($users, $response->get('total'), $offset);
	}


	/**
	 * List all users being online
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=2#get_online_users
	 * @param int $limit
	 * @param int $offset
	 * @return \Netzmacht\Tapatalk\Api\Users\OnlineUserResult|OnlineUser[]
	 */
	public function getOnlineUsers($limit = null, $offset = 0)
	{
		$params = array();

		return $this->queryOnlineUsers($limit, $offset, $params);
	}


	/**
	 * List all users being online in a forum
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=2#get_online_users
	 * @param int $id forum id
	 * @param int $limit
	 * @param int $offset
	 * @return \Netzmacht\Tapatalk\Api\Users\OnlineUserResult|OnlineUser[]
	 */
	public function getForumOnlineUsers($id, $limit = null, $offset = 0)
	{
		$params = array('area' => 'forum', 'id' => (string)$id);

		return $this->queryOnlineUsers($limit, $offset, $params);
	}


	/**
	 * List all users being online in a topic
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=2#get_online_users
	 * @param int $id forum id
	 * @param int $limit
	 * @param int $offset
	 * @return \Netzmacht\Tapatalk\Api\Users\OnlineUserResult|OnlineUser[]
	 */
	public function getTopicOnlineUsers($id, $limit = null, $offset = 0)
	{
		$params = array('area' => 'topic', 'id' => (string)$id);

		return $this->queryOnlineUsers($limit, $offset, $params);
	}

	/**
	 * Search users by keywords
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=2#search_user
	 * @param string $keywords
	 * @param int $limit
	 * @param int $offset
	 * @return \Netzmacht\Tapatalk\Result|User[]
	 */
	public function search($keywords, $limit = 20, $offset = 0)
	{
		$response = $this->transport->createMethodCall('search_user')
			->set('keywords', $keywords, true)
			->addParams($this->addPagination($limit, $offset))
			->call();

		$this->assert()->noResultState($response);
		$users = array();

		foreach($response->get('list') as $user) {
			$users[] = User::fromResponse($this->transport, $user);
		}

		return new Result($users, $response->get('total'), $offset);
	}


	/**
	 * @see http://tapatalk.com/api/api_section.php?id=2#ignore_user
	 * @param $userId
	 * @param bool $ignore
	 */
	public function ignoreUser($userId, $ignore = true)
	{
		$params = array(
			'user_id' => (string)$userId,
			'mode'    => $ignore ? 1 : 0
		);

		$response = $this->transport->call('ignore_user', $params);
		$this->assert()->resultSuccess($response);
	}


	/**
	 * Follow or unfollow a user
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=9#follow
	 * @see http://tapatalk.com/api/api_section.php?id=9#unfollow
	 *
	 * @param int $userId
	 * @param bool $follow
	 */
	public function followUser($userId, $follow = true)
	{
		$method = $follow ? 'follow' : 'unfollow';
		$params = array('user_id' => (string)$userId);

		$response = $this->transport->call($method, $params);
		$this->assert()->resultSuccess($response);
	}


	/**
	 * List users which account user is following
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=9#get_follower
	 * @return Result|FollowUser[]
	 */
	public function getFollowers()
	{
		return $this->queryFollowers('get_follower');
	}


	/**
	 * List user who are following the account user
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=9#get_following
	 * @return Result|FollowUser[]
	 */
	public function getFollowing()
	{
		return $this->queryFollowers('get_following');
	}

	/**
	 * @throws \Netzmacht\Tapatalk\Exception\NotImplementedException
	 */
	public function downloadAvatar()
	{
		//$this->assert()->featureSupported(Config::FEATURE_DOWNLOAD_AVATAR);

		// TODO: Implement
		throw new NotImplementedException('Users::downloadAvatar is not implemented');
	}


	/**
	 * Change user reputation
	 *
	 * @see http://tapatalk.com/api/api_section.php?id=9#set_reputation
	 * @param $userId
	 * @param string $reputation
	 */
	public function changeUserReputation($userId, $reputation = Users::REPUTATION_POSITIVE)
	{
		$this->assertValidReputationMode($reputation);

		$params = array(
			'user_id' => (string)$userId,
			'mode'    => $reputation
		);

		$response = $this->transport->call('set_reputation', $params);
		$this->assert()->resultSuccess($response);
	}


	/**
	 * @param $limit
	 * @param $offset
	 * @param array $params
	 * @param bool $asString
	 * @return array
	 */
	private function addPagination($limit, $offset, $params = array(), $asString = false)
	{
		if($limit !== null) {
			$params['perpage'] = $limit;
			$params['page']    = abs($offset / $limit);

			if($offset !== null) {
				$params['page'] = abs($offset / $limit);

				if($params['page'] < 1) {
					$params['page'] = 1;
				}
			} else {
				$params['page'] = 1;
			}

			if($asString) {
				$params['page']    = (string)$params['page'];
				$params['perpage'] = (string)$params['perpage'];
			}
		}

		return $params;
	}


	/**
	 * @param $limit
	 * @param $offset
	 * @param array $params
	 * @return OnlineUserResult
	 */
	private function queryOnlineUsers($limit, $offset, $params = array())
	{
		if($limit) {
			$params = $this->addPagination($limit, $offset, $params, true);
		}

		$response = $this->transport->call('get_online_users', $params);
		$users    = array();

		$this->assert()->resultSuccess($response);

		foreach($response->get('list') as $user) {
			$users[] = OnlineUser::fromResponse($user);
		}

		return new OnlineUserResult($users, $response->get('member_count'), $offset, $response->get('guest_count'));
	}

	/**
	 * @param $method
	 * @return Result|FollowUser[]
	 */
	private function queryFollowers($method)
	{
		$response = $this->transport->call($method);
		$this->assert()->noResultState($response);

		$users = array();
		foreach($response->get('list') as $user) {
			$users[] = FollowUser::fromResponse($user);
		}

		return new Result($users, $response->get('total_count'));
	}


	/**
	 * @param $identifier
	 * @throws \InvalidArgumentException
	 */
	private function assertValidIdentifier($identifier)
	{
		if($identifier != static::USER_ID && $identifier != static::USERNAME) {
			throw new \InvalidArgumentException('Given user identifier ' . $identifier . ' type not supported');
		}
	}


	/**
	 * @param $mode
	 * @throws \InvalidArgumentException
	 */
	private function assertValidRecommendMode($mode)
	{
		if($mode != static::RECOMMENDED_ALL && $mode != static::RECOMMENDED_NON_TAPATALK) {
			throw new \InvalidArgumentException('Invalid recommend user mode given ' . $mode . '');
		}
	}


	/**
	 * @param $reputation
	 * @throws \InvalidArgumentException
	 */
	private function assertValidReputationMode($reputation)
	{
		if($reputation != static::REPUTATION_POSITIVE && $reputation != static::REPUTATION_NEGATIVE) {
			throw new \InvalidArgumentException('Invalid reputation mode given: ' . $reputation);
		}
	}

} 