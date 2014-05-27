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


interface UploadHandler
{

	/**
	 * @param $path
	 * @param $file
	 * @param array $params
	 * @return mixed
	 */
	public function upload($path, $file, $params = array());

}