<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Users;


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class RecommendedUser extends User
{
	const TYPE_CONTACT   = 'contact';
	const TYPE_FOLLOWING = 'following';
	const TYPE_FOLLOWER  = 'follower';
	const TYPE_LIKE      = 'like';
	const TYPE_LIKED     = 'liked';
	const TYPE_WATCH     = 'watch';


	/**
	 * @var string
	 */
	private $encryptedEmail;

	/**
	 * @var string
	 */
	private $type;


	/**
	 * @param int $userId
	 * @param string $username
	 * @param string $avatar
	 * @param $type
	 * @param $encryptedEmail
	 */
	function __construct($userId, $username, $avatar, $type, $encryptedEmail)
	{
		parent::__construct($userId, $username, $avatar);

		$this->type           = $type;
		$this->encryptedEmail = $encryptedEmail;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return RecommendedUser
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('user_id'),
			$response->get('username', true),
			$response->get('icon_url'),
			$response->get('type'),
			$response->get('enc_email')
		);
	}


	/**
	 * @return string
	 */
	public function getEncryptedEmail()
	{
		return $this->encryptedEmail;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

} 