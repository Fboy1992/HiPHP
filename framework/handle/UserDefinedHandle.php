<?php
/**
 * 用户自定义处理逻辑
 */
namespace framework\handle;

use framework\App;
use framework\handle\Handle;
use framework\exception\CoreHttpException;

class UserDefinedHandle extends Handle
{
	public function __construct()
	{}
	
	public function register(App $app)
	{
		$config = $app::$container->getSingle('config');
		
		foreach ($config->config['module'] as $v) {
			$v = strtolower($v);
			$className = "app\\{$v}\\logics\\UserDefinedCase";
			new $className($app);
		}
	}
}