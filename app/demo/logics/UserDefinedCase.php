<?php

namespace app\demo\logics;

use framework\App;

class UserDefinedCase
{
	private $map = [
		// 演示加载自定义网关
		// ‘app\demo\logics\gateway\Entrance’
		// 
		// =============== // 
	];

	public function __construct(App $app)
	{
		foreach ($this->map as $as) {
			new $v($app);
		}
	}
}
