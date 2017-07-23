<?php
/**
 * 自动加载类
 */
namespace framework;

use framework\App;
use Exception;

class Load
{
	// 类名映射
	public static $map = [];
	// 类名空间映射
	public static $namespaceMap = [];

	// 应用启动注册
	public static function register(App $app)
	{
		self::$namespaceMap = [
			'framework' => $app->rootPath
		];

		spl_autoload_register(['framework\Load', 'autoload']);

	}

	private static function autoload($class)
	{
		$classOrigin = $class;
		// 类名不能为小写
		$classInfo = explode('\\', $class);
		$className = array_pop($classInfo);
		foreach ($classInfo as &$v) {
			$v = strtolower($v);
		}
		array_push($classInfo, $className);
		$class = implode('\\', $classInfo);
		
		$path = self::$namespaceMap['framework'];
		$classPath = $path . '/' . str_replace('\\', '/', $class) . '.php';
		
		// composer

		self::$map[$classOrigin] = $classPath;
		require $classPath;
	}
}
