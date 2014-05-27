<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Account;


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class LoginByTapatalkIdResult
{
	const STATUS_SUCCESS                   = null;
	const STATUS_USERNAME_OCCUPIED         = 1;
	const STATUS_USER_CREDENTIALS_REQUIRED = 2;
	const STATUS_EMAIL_DOES_NOT_MATCH      = 3;

	/**
	 * @var int
	 */
	private $status;

	/**
	 *  bool
	 */
	private $isNewRegistered;

	/**
	 * @var int
	 */
	private $previewTopicId;


	/**
	 * @param $status
	 * @param $isNewRegistered
	 * @param $previewTopicId
	 */
	function __construct($status, $isNewRegistered, $previewTopicId)
	{
		$this->status          = $status;
		$this->isNewRegistered = $isNewRegistered;
		$this->previewTopicId  = $previewTopicId;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('status'),
			$response->get('register'),
			$response->get('preview_topic_id')
		);
	}


	/**
	 * @return mixed
	 */
	public function isNewRegistered()
	{
		return $this->isNewRegistered;
	}

	/**
	 * @return int
	 */
	public function getPreviewTopicId()
	{
		return $this->previewTopicId;
	}

	/**
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param $status
	 * @return bool
	 */
	public function isStatus($status)
	{
		return ($status === $this->status);
	}

} 