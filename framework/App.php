<?php
/**
 * 应用类
 */
namespace framework;

use framework\Container;
use framework\Load;
use Exception;
use Closure;

class App
{
	// 框架加载一系列处理类集合
	private $handlesList = [];
	// 框架实例
	public static $app;
	// 服务容器
	public static $container;
	// 请求体
	private $request;
	// 响应对象
	private $responseData;
	// 根目录
	private $rootPath;

	// 构造函数
	public function __construct($rootPath, Closure $loader)
	{
		$this->rootPath = $rootPath;

		// 注册自加载
		$loader();
		// 自动加载
		Load::register($this);

		self::$app = $this;
		self::$container = new Container();
	}

	//__get
	public function __get($name = '')
	{
		return $this->$name;
	}
	//__set
	public function __set($name = '', $value = '')
	{
		$this->$name = $value;
	}

	//注册框架运行时，一系列处理类
	public function load(Closure $handle)
	{
		$this->handlesList[] = $handle;
	}

	//运行应用
	public function run(Closure $request)
	{
		self::$container->setSingle('request', $request);
		foreach ($this->handlesList as $handle) {
			$instance = $handle();
			self::$container->setSingle(get_class($instance), $instance);


			//
			$instance->register($this);

		}
	}

	public function response(Closure $closeure)
	{
		$closeure()->response($this->responseData);
	}
}
