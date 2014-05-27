<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Transport;


interface Serializer
{

	/**
	 * @param $value
	 * @return mixed
	 */
	public function serialize($value);


	/**
	 * @param $value
	 * @return mixed
	 */
	public function deserialize($value);

} 