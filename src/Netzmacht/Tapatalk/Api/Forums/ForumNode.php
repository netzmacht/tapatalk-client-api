<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Forums;

use Netzmacht\Tapatalk\Transport\MethodCallResponse;


/**
 * Class Forum
 * @package Netzmacht\Tapatalk\Api\Forums
 */
class ForumNode extends Forum
{
	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var int
	 */
	private $parentId;

	/**
	 * @var bool
	 */
	private $isSubscribed;

	/**
	 * @var bool
	 */
	private $canUserSubscribe;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var bool
	 */
	private $isSubOnly;

	/**
	 * @var ForumNode[]
	 */
	private $children;


	/**
	 * @param $id
	 * @param $name
	 * @param $description
	 * @param $logo
	 * @param $children
	 * @param $parentId
	 * @param $url
	 * @param $hasNewPost
	 * @param $canUserSubscribe
	 * @param $isProtected
	 * @param $isSubOnly
	 * @param $isSubscribed
	 */
	function __construct($id, $name, $description, $logo, $children, $parentId, $url, $hasNewPost, $canUserSubscribe, $isProtected, $isSubscribed, $isSubOnly)
	{
		parent::__construct($id, $name, $logo, $isProtected, $hasNewPost);

		$this->description      = $description;
		$this->children         = $children;
		$this->parentId         = $parentId;
		$this->url              = $url;
		$this->canUserSubscribe = $canUserSubscribe;
		$this->isSubOnly        = $isSubOnly;
		$this->isSubscribed     = $isSubscribed;
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
			$response->get('description', true),
			$response->get('logo_url'),
			static::createChildren($response),
			$response->get('parent_id'),
			$response->get('url'),
			$response->get('new_post'),
			$response->get('can_subscribe'),
			$response->get('is_protected'),
			$response->get('is_subscribed'),
			$response->get('sub_only')
		);
	}


	/**
	 * @param $response
	 * @return array|ForumNode[]
	 */
	private static function createChildren(MethodCallResponse $response)
	{
		$children = array();

		foreach($response->get('child', false, array()) as $child) {
			$children[] = static::fromResponse($child);
		}

		return $children;
	}


	/**
	 * @return boolean
	 */
	public function canUserSubscribe()
	{
		return $this->canUserSubscribe;
	}


	/**
	 * @return ForumNode[]
	 */
	public function getChildren()
	{
		return $this->children;
	}


	/**
	 * @return bool
	 */
	public function hasChildren()
	{
		return (!empty($this->children));
	}


	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}


	/**
	 * @return boolean
	 */
	public function isSubOnly()
	{
		return $this->isSubOnly;
	}


	/**
	 * @return boolean
	 */
	public function isSubscribed()
	{
		return $this->isSubscribed;
	}


	/**
	 * @return int
	 */
	public function getParentId()
	{
		return $this->parentId;
	}


	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}


	/**
	 * @return bool
	 */
	public function isLink()
	{
		return ($this->url !== null);
	}

} 