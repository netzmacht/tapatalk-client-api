<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Exception;


use Exception;

class DisabledPushTypeException extends \Exception
{
	/**
	 * @var int
	 */
	private $version;


	/**
	 * @param string $message
	 * @param null $version
	 * @param int $code
	 * @param Exception $previous
	 */
	public function __construct($message = "", $version = null, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);

		$this->version = $version;
	}


	/**
	 * @return mixed
	 */
	public function getVersion()
	{
		return $this->version;
	}


} 