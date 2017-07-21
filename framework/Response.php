<?php
/**
 * 响应类
 */
namespace framework;

class Response
{
	public $response = [
		'code'=>200,
		'msg'=>'success',
		'data'=>[
			'tip' => 'hello HiPHP!',
			'author' => 'aizsfgk'
		]
	];
	public function response($response = [])
	{
		header('Content-Type:Application/json; Charset=utf-8');
		die(json_encode(
			$this->response,
			JSON_UNESCAPED_UNICODE
		) . "\n");
	}
}
