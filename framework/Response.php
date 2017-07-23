<?php
/**
 * 响应类
 */
namespace framework;

use framework\App;
class Response
{
	public $response;
	
	public function response($response = [])
	{

		//dump($app::$container);
		header('Content-Type:Application/json; Charset=utf-8');
		die(json_encode(
			$response,
			JSON_UNESCAPED_UNICODE
		) . "\n");
	}
}
