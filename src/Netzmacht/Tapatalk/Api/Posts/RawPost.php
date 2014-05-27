<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Posts;
use Netzmacht\Tapatalk\Api\Attachments\Attachment;
use Netzmacht\Tapatalk\Api\Attachments\FullAttachment;
use Netzmacht\Tapatalk\Transport\MethodCall;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;


/**
 * Class RawPost
 * @package Netzmacht\Tapatalk\Api\Posts
 */
class RawPost
{

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var bool
	 */
	private $canAddReason;

	/**
	 * @var string
	 */
	private $editReason;

	/**
	 * @var int
	 */
	private $attachmentGroupId;

	/**
	 * @var array
	 */
	private $attachments;


	/**
	 * @param $id
	 * @param $title
	 * @param $content
	 * @param $attachmentGroupId
	 * @param $attachments
	 * @param $canAddReason
	 * @param $editReason
	 */
	function __construct($id, $title, $content, $attachmentGroupId, $attachments, $canAddReason, $editReason)
	{
		$this->id                = $id;
		$this->title             = $title;
		$this->content           = $content;
		$this->attachmentGroupId = $attachmentGroupId;
		$this->attachments       = $attachments;
		$this->canAddReason      = $canAddReason;
		$this->editReason        = $editReason;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		$attachments = array();

		foreach($response->get('attachments', false, array()) as $attachment) {
			$attachments[] = FullAttachment::fromResponse($attachment);
		}

		return new static(
			$response->get('post_id'),
			$response->get('post_title', true),
			$response->get('post_content', true),
			$response->get('group_id'),
			$attachments,
			$response->get('show_reason'),
			$response->get('edit_reason')
		);
	}


	/**
	 * @return int
	 */
	public function getAttachmentGroupId()
	{
		return $this->attachmentGroupId;
	}

	/**
	 * @return array
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}

	/**
	 * @return bool
	 */
	public function hasAttachments()
	{
		return !empty($this->attachments);
	}


	/**
	 * @return boolean
	 */
	public function canUserAddReason()
	{
		return $this->canAddReason;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @return string
	 */
	public function getLastEditReason()
	{
		return $this->editReason;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}



} 