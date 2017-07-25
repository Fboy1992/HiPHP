<?php
/**
 * nosql 处理句柄
 */
namespace framework\handle;

use framework\App;
use framework\handle\Handle;
use framework\exception\CoreHttpException;

class NosqlHandle extends Handle
{
	public function __construct()
	{}

	public function register(App $app)
	{
		$config = $app::$container->getSingle('config');
		if (empty($config->config['nosql'])) {
			return;
		}
		$config = explode(',', $config->config['nosql']);
		foreach ($config as $v) {
			$className = 'framework\nosql\\' . ucfirst($v);

			App::$container->setSingle($v, function () use ($className) {
				// 懒加载 lazy load
				return $className::init();
			});
		}
	}
}
