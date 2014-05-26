<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 26.05.14
 * Time: 10:35
 */

namespace Netzmacht\Tapatalk\Api\Posts;


use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\DateTime;

class Edit extends User
{
	/**
	 * @var \DateTime
	 */
	private $editedAt;

	/**
	 * @var string
	 */
	private $reason;


	/**
	 * @param int $id
	 * @param string $username
	 * @param string $editedAt
	 * @param $reason
	 */
	function __construct($id, $username, $editedAt, $reason)
	{
		parent::__construct($id, $username, null);

		$this->editedAt = $editedAt;
		$this->reason   = $reason;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return Edit
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('editor_id'),
			$response->get('editor_name', true),
			DateTime::createFromTimestamp($response->get('edit_time')),
			$response->get('edit_reason', true)
		);
	}


	/**
	 * @return \DateTime
	 */
	public function getEditedAt()
	{
		return $this->editedAt;
	}


	/**
	 * @return string
	 */
	public function getReason()
	{
		return $this->reason;
	}

}