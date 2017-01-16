<?php
namespace Repository;

/**
 * Created by PhpStorm.
 * User: yonh
 * Date: 1/16/17
 * Time: 10:14 AM
 */
class Git
{
	private $repository_url;
	private $repository_path;

	public function __construct($repository_url, $repository_path) {
		$this->repository_url = $repository_url;
		$this->repository_path = $repository_path;
	}

	public function clone_src() {
		if (!file_exists($this->repository_path)) {
			mkdir($this->repository_path, 0700, true);
		}
		$this->execute("clone " . escapeshellarg($this->repository_url));
	}

	private function execute($command) {
		$command = 'git -C ' . escapeshellarg($this->repository_path) . ' ' . $command;
		if (DIRECTORY_SEPARATOR == '/') {
			$command = 'LC_ALL=en_US.UTF-8 ' . $command;
		}
		exec($command, $output, $returnValue);

		if ($returnValue !== 0) {
			throw new \RuntimeException(implode("\r\n", $output));
		}

		return $output;
	}

	/**
	 * @return mixed
	 */
	public function getRepositoryUrl()
	{
		return $this->repository_url;
	}

	/**
	 * @param mixed $repository_url
	 */
	public function setRepositoryUrl($repository_url)
	{
		$this->repository_url = $repository_url;
	}

	/**
	 * @return mixed
	 */
	public function getRepositoryPath()
	{
		return $this->repository_path;
	}

	/**
	 * @param mixed $repository_path
	 */
	public function setRepositoryPath($repository_path)
	{
		$this->repository_path = $repository_path;
	}


}