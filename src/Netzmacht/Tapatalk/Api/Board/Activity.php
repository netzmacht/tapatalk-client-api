<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Board;

use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\DateTime;


/**
 * Class Alert
 * @package Netzmacht\Tapatalk\Api\Board
 */
class Activity
{
	const TYPE_POST            = 'post';
	const TYPE_USER            = 'user';
	const TYPE_PRIVATE_MESSAGE = 'om';
	const TYPE_SUB             = 'sub';


	/**
	 * @var
	 */
	private $user;


	/**
	 * @var
	 */
	private $contentId;

	/**
	 * @var
	 */
	private $contentType;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var \DateTime
	 */
	private $createdAt;

	/**
	 * @param $message
	 * @param $contentId
	 * @param $contentType
	 * @param $user
	 * @param $createdAt
	 */
	function __construct($message, $contentId, $contentType, $user, $createdAt)
	{
		$this->message     = $message;
		$this->contentId   = $contentId;
		$this->contentType = $contentType;
		$this->user        = $user;
		$this->createdAt   = $createdAt;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('message', true),
			$response->get('content_id'),
			$response->get('content_type'),
			new User($response->get('user_id'), $response->get('username', true), $response->get('icon_url')),
			DateTime::createFromTimestamp($response->get('timestamp'))
		);
	}

	/**
	 * @return mixed
	 */
	public function getContentId()
	{
		return $this->contentId;
	}

	/**
	 * @return mixed
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

} 