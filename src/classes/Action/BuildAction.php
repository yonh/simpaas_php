<?php
/**
 * Created by PhpStorm.
 * User: yonh
 * Date: 16-12-8
 * Time: 下午3:57
 */

namespace Action;


use Slim\Http\Request;

class BuildAction
{
	/** @var  Request */
	private $request;

	public function __construct($request)
	{
		$this->request = $request;
	}

	public function invoke() {

		$build_file = $this->request->getParam('build_file');
		$build_src = $this->request->getParam('build_src');

		if (!file_exists(dirname($build_file))) {
			mkdir(dirname($build_file), 0700, true);
		}

		$zip=new \ZipArchive();
		if($zip->open($build_file, \ZipArchive::OVERWRITE)=== TRUE){
			$this->addFileToZip($build_src, $zip, $build_src); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
			$zip->close(); //关闭处理的zip文件
			file_put_contents('/tmp/fack', $build_file);
		} else {
			file_put_contents('/tmp/fack', 'abc');
		}
		return true;
	}


	function addFileToZip($path,$zip, $rootpath){
		$handler=opendir($path); //打开当前文件夹由$path指定。
		while(($filename=readdir($handler))!==false){
			if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
				if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
					$this->addFileToZip($path."/".$filename, $zip, $rootpath);
				}else{ //将文件加入zip对象
					$zipname = substr($path."/".$filename, strlen($rootpath));
					$zip->addFile($path."/".$filename, $zipname);
				}
			}
	}
		@closedir($path);
	}

}