<?php
namespace Action;

use Net\CurlHelper;
use NoName\SSHClient;
use NoName\Version;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Repository\Git;
use Slim\Views\Twig;
use Docker\DockerClient;
use Docker\Docker;

class MyApp
{
	/**
	 * Stores an instance of the Slim application.
	 *
	 * @var \Slim\App
	 */
	private $app;
	/** @var  Twig */
	private $view;

	public function __construct() {

		$config['displayErrorDetails'] = true;
		//$config['addContentLengthHeader'] = false;

//		$config['db']['host']   = "localhost";
//		$config['db']['user']   = "user";
//		$config['db']['pass']   = "password";
//		$config['db']['dbname'] = "exampleapp";

		$app = new \Slim\App(["settings" => $config]);

		$container = $app->getContainer();

		$container['view'] = function ($container) {
			$view = new Twig(__DIR__ . '/../../template', [
			//	'cache' => __DIR__ . '/../../cache'
			]);
			$view->addExtension(new \Twig_Extension_Debug());

			return $view;
		};

		$app->get('/', function (Request $request, Response $response) {
			$sshclient = new SSHClient();
			$public_key = htmlentities($sshclient->getPublicKey(), ENT_QUOTES);

			$client = new DockerClient([
				                           'remote_socket' => 'tcp://192.168.50.45:2375',
				                           'ssl' => false,
			                           ]);
			$docker = new Docker($client);
			$containers = $docker->getContainerManager()->findAll();

			echo "<pre>";
			print_r($containers);
			echo "</pre>";

			$data = array(
				'containers'=>$containers,
				'public_key'=>$public_key
			);

			return $this->view->render($response, "index.twig", $data);

		});

		$app->get('/stop/{id}',function(Request $request, Response $response, $args) {
			$docker = new \NoName\Docker();
			$docker->rm($args['id']);
		});

		$app->post('/run', function(Request $request, Response $response) {
			$params = $request->getParsedBody();

			if (isset($params['project_port'])) {
				$port = intval($params['port']);
				$docker = new \NoName\Docker();
				$docker->run("yonh/apache2-php", "$port");
			}



			//$file = request->get('file');
//			$path ="/home/apache/rep";
//			$rpath = "ssh://git@192.168.50.45:2222/yonh/test.git";
//
//			$git = new Git($rpath, $path);
//			$git->clone_src();






		});


		$app->get('/test', function (Request $request, Response $response) {
//			$git = new Git("https://github.com/yonh/git_init", "/tmp/abc");
//			$git->clone_src();

			$docker = new \NoName\Docker();
			$docker->run("yonh/apache2-php", "10081");


		});



		$this->app = $app;
	}
	/**
	 * Get an instance of the application.
	 *
	 * @return \Slim\App
	 */
	public function get()
	{
		return $this->app;
	}
}
