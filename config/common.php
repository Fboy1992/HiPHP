<?php
/**
 * 基本常规配置
 */
use framework\Helper;

return [
	// 默认模块
	'module' => [
		'demo',
	],

	// 默认路由
	'route' => [
		'default_module' => 'demo',
		'default_controller' => 'index',
		'default_action' => 'hello',
	],

	// 响应结果是否使用 rest风格
	'rest_response' => true,

	// 默认时区
	'default_timezone' => 'Asia/Shanghai',

];
