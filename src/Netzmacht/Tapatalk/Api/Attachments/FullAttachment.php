<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Attachments;


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class FullAttachment extends Attachment
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $fileSize;

	/**
	 * @var
	 */
	private $fileName;


	/**
	 * @param $id
	 * @param $fileName
	 * @param $url
	 * @param $contentType
	 * @param $thumbnail
	 * @param null $fileSize
	 */
	function __construct($id, $fileName, $url, $contentType, $thumbnail, $fileSize = null)
	{
		parent::__construct($url, $contentType, $thumbnail);

		$this->id       = $id;
		$this->fileSize = $fileSize;
		$this->fileName = $fileName;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('attachment_id'),
			$response->get('filename', true),
			$response->get('url'),
			$response->get('content_type'),
			$response->get('thumbnail_url'),
			$response->get('filesize')
		);
	}


	/**
	 * @return int
	 */
	public function getFileSize()
	{
		return $this->fileSize;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}


} 