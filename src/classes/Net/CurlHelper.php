<?php
namespace Net;
/**
 * Created by PhpStorm.
 * User: yonh
 * Date: 1/10/17
 * Time: 3:02 PM
 */
class CurlHelper
{
	public function post($url, $session_id)
	{

	}

	public function get($url, $session_id)
	{
		$ch     = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookie
		curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . $session_id);
		$rs = curl_exec($ch); //执行cURL抓取页面内容
		curl_close($ch);

		return $rs;
	}
}