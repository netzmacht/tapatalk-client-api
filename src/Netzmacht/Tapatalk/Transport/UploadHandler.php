<?php

namespace Netzmacht\Tapatalk\Transport;


interface UploadHandler
{

	/**
	 * @param $path
	 * @param $file
	 * @param array $params
	 * @return mixed
	 */
	public function upload($path, $file, $params=array());

}