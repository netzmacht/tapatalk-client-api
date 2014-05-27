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


use Netzmacht\Tapatalk\Transport\MethodCallResponse;

class Smilie
{
	/**
	 * @var string
	 */
	private $category;

	/**
	 * @var string
	 */
	private $code;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $title;


	/**
	 * @param $code
	 * @param $title
	 * @param $url
	 * @param $category
	 */
	function __construct($code, $title, $url, $category)
	{
		$this->code     = $code;
		$this->title    = $title;
		$this->url      = $url;
		$this->category = $category;
	}


	/**
	 * @param MethodCallResponse $response
	 * @param $category
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response, $category=null)
	{
		return new static(
			$response->get('code', true),
			$response->get('title', true),
			$response->get('url'),
			$category
		);
	}


	/**
	 * @return string
	 */
	public function getCategory()
	{
		return $this->category;
	}


	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}


	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

} 