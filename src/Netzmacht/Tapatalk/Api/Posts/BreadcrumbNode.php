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
use Netzmacht\Tapatalk\Transport\MethodCallResponse;


/**
 * Class Breadcrumb
 * @package Netzmacht\Tapatalk\Api\Posts
 */
class BreadcrumbNode
{
	/**
	 * @var int
	 */
	private $forumId;

	/**
	 * @var string
	 */
	private $forumName;

	/**
	 * @var bool
	 */
	private $subOnly;

	/**
	 * @param $forumId
	 * @param $forumName
	 * @param $subOnly
	 */
	function __construct($forumId, $forumName, $subOnly)
	{
		$this->forumId   = $forumId;
		$this->forumName = $forumName;
		$this->subOnly   = $subOnly;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		return new static(
			$response->get('forum_id'),
			$response->get('forum_name', true),
			$response->get('sub_only')
		);
	}

	/**
	 * @return int
	 */
	public function getForumId()
	{
		return $this->forumId;
	}

	/**
	 * @return string
	 */
	public function getForumName()
	{
		return $this->forumName;
	}

	/**
	 * @return boolean
	 */
	public function getSubOnly()
	{
		return $this->subOnly;
	}

} 