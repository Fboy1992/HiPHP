<?php
/**
 * 
 */

namespace app\demo\controllers;

use framework\App;
use app\demo\models\User;
use Exception;

class Index
{

	public function hello()
	{
		
		return [
			'code'   => 200,
			'author' => 'aizsfgk',
			'message'=> 'hello HiPHP'
		];
	}

	public function user()
	{

		// 获取请求的参数, // 路由解析
		// 
		// 鉴权
		$request = App::$container->getSingle('request');
		$id      = intval($request->get('id', 2));
		$cf      = App::$container->getSingle('config');
		
		// 数据库查找数据 
		$user = new User();
        $res = $user->findUserInfoByid($id);
		
		// 返回数据
		return empty($res) ? ['time'=>date("Y-m-d H:i:s"), 'debug'=>true] : $res;
	}
}
