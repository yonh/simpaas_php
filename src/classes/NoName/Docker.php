<?php
/**
 * Created by PhpStorm.
 * User: yonh
 * Date: 1/16/17
 * Time: 10:47 AM
 */

namespace NoName;


use ArrayObject;
use Docker\API\Model\Container;
use Docker\API\Model\ContainerConfig;
use Docker\API\Model\HostConfig;
use Docker\API\Model\PortBinding;
use Docker\DockerClient;
use Symfony\Component\Config\Definition\Exception\Exception;

class Docker
{
	private function getDocker() {
		$client = new DockerClient([
			                           'remote_socket' => 'tcp://192.168.50.45:2375',
			                           'ssl' => false,
		                           ]);

		$docker = new \Docker\Docker($client);

		return $docker;
	}
	public function run($image,$port = null,$path_binds = null) {
		$docker = $this->getDocker();
		$containerManager = $docker->getContainerManager();

		$containerConfig = new ContainerConfig();
		$containerConfig->setImage($image);

		$hostConfig = new HostConfig();

		if (!is_null($path_binds)) {
			$hostConfig->setBinds($path_binds);
		}

		if (!is_null($port)) {
			$hostPort80Binding = new PortBinding();
			$hostPort80Binding->setHostIp("0.0.0.0");

			$hostPort80Binding->setHostPort($port);


			$mapPorts = new ArrayObject();
			$mapPorts["80/tcp"] = [$hostPort80Binding];
			$hostConfig->setPortBindings($mapPorts);
		}

		$containerConfig->setHostConfig($hostConfig);

		try {
			$containerCreateResult = $containerManager->create($containerConfig);

			$containerManager->start($containerCreateResult->getId());

		} catch (Exception $e) {
			print_r($e->getMessage());
		}

//		return $containerCreateResult;
	}

	public function rm($id) {
//		$container = new Container();
//		$container->setId($id);

		$this->getDocker()->getContainerManager()->stop($id);
		$this->getDocker()->getContainerManager()->remove($id);

	}
}