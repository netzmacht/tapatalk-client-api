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


class Attachment
{
	const TYPE_IMAGE = 'image';
	const TYPE_PDF   = 'odf';
	const TYPE_OTHER = 'other';

	/**
	 * @var string
	 */
	private $contentType;

	/**
	 * @var string
	 */
	private $thumbnail;

	/**
	 * @var string
	 */
	private $url;


	/**
	 * @param $url
	 * @param $contentType
	 * @param $thumbnail
	 */
	function __construct($url, $contentType, $thumbnail)
	{
		$this->url         = $url;
		$this->contentType = $contentType;
		$this->thumbnail   = $thumbnail;
	}

	/**
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * @return string
	 */
	public function getThumbnail()
	{
		return $this->thumbnail;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

} 