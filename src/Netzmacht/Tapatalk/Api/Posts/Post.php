<?php

namespace Netzmacht\Tapatalk\Api\Posts;


use Netzmacht\Tapatalk\Api\Attachments\Attachment;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\DateTime;
use Netzmacht\Tapatalk\Transport;

class Post
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var bool
	 */
	private $canEdit;

	/**
	 * @var bool
	 */
	private $canDelete;

	/**
	 * @var \DateTime
	 */
	private $updatedAt;

	/**
	 * @var
	 */
	private $attachments;

	/**
	 * @param $id
	 * @param $content
	 * @param $updatedAt
	 * @param $attachments
	 * @param $canEdit
	 * @param $canDelete
	 */
	function __construct($id, $content, $updatedAt, $attachments, $canEdit, $canDelete)
	{
		$this->id          = $id;
		$this->content     = $content;
		$this->updatedAt   = $updatedAt;
		$this->attachments = $attachments;
		$this->canEdit     = $canEdit;
		$this->canDelete   = $canDelete;
	}

	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		$attachments = array();

		foreach($response->get('attachments', false, array()) as $attachment) {
			$attachments[] = new Attachment(
				$attachment->get('url'),
				$attachment->get('content_type', true),
				$attachment->get('thumbnail_url')
			);
		}

		return new static(
			$response->get('post_id'),
			$response->get('post_content', true),
			DateTime::createFromTimestamp($response->get('post_time') ?: $response->get('timestamp')),
			$attachments,
			$response->get('can_edit', false, true),
			$response->get('can_delete', false, true)
		);
	}


	/**
	 * @return array
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}


	/**
	 * @return boolean
	 */
	public function casUserDelete()
	{
		return $this->canDelete;
	}

	/**
	 * @return boolean
	 */
	public function canUserEdit()
	{
		return $this->canEdit;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

} 