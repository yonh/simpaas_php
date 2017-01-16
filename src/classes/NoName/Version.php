<?php
namespace NoName;

/**
 * Created by PhpStorm.
 * User: yonh
 * Date: 1/10/17
 * Time: 7:08 PM
 */
class Version
{
	public function getVersions() {
		$dir = __DIR__ . "/../../../hash";
		$dirs = [];
		foreach (new \DirectoryIterator($dir) as $file) {
			if ($file->isDir() && !$file->isDot()) {
				$dirs[] = $file->getFilename();
			}
		}

		return $dirs;
	}
}