<?php
/**
 * 响应类
 */
namespace framework;

class Response
{
	public function response($data)
	{
		header('Content-Type:Application/json; Charset=utf-8');
		die(json_encode(
			$data,
			JSON_UNESCAPED_UNICODE
		));
	}
}
