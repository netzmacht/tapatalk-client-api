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

class Smilies
{
	/**
	 * @var Smilie[]
	 */
	private $smilies = array();

	/**
	 * @var array
	 */
	private $categories = array();


	/**
	 * @param $smilies
	 */
	function __construct($smilies)
	{
		$this->smilies    = $smilies;
		$this->categories = $this->extractCategories();
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		$smilies = array();

		foreach($response->get('list') as $category => $list) {
			foreach($list as $smilie) {
				$smilies[] = Smilie::fromResponse($smilie, $category);
			}
		}

		return new static($smilies);
	}


	/**
	 * @return array
	 */
	public function getCategories()
	{
		return $this->categories;
	}

	/**
	 * @return \Netzmacht\Tapatalk\Api\Board\Smilie[]
	 */
	public function getSmilies()
	{
		return $this->smilies;
	}


	/**
	 * @param $category
	 * @return Smilie[]
	 */
	public function getSmiliesByCategory($category)
	{
		$smilies = array();

		if(in_array($category, $this->categories)) {
			foreach($this->smilies as $smilie) {
				if($smilie->getCategory() == $category) {
					$smilies[] = $smilie;
				}
			}
		}

		return $smilies;
	}


	/**
	 * @param $code
	 * @return Smilie|null
	 */
	public function getSmilieByCode($code)
	{
		foreach($this->smilies as $smilie) {
			if($smilie->getCode() == $code) {
				return $smilie;
			}
		}

		return null;
	}


	/**
	 * @return array
	 */
	private function extractCategories()
	{
		$categories = array();

		foreach($this->smilies as $smilie) {
			$categories[$smilie->getCategory()] = true;
		}

		return array_keys($categories);
	}

} 