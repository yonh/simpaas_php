<?php
namespace NoName;

/**
 * Created by PhpStorm.
 * User: yonh
 * Date: 1/10/17
 * Time: 7:08 PM
 */
class SSHClient
{
	public function getPublicKey() {
		$key = file_get_contents('/home/apache/.ssh/id_rsa.pub');

		return $key;
	}
}