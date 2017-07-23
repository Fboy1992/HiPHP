<?php
/**
 * Function: 路由系统句柄
 * author: aizsfgk
 * Date: 2017-07-23
 * 原理：
 * 		走统一的入口文件，获取请求的uri参数，通过$_SERVER超全局变量解析参数
 * 		匹配不同的参数，交给不同的类处理，执行分发渠道功能
 * 		restful 风格的路由
 */
namespace framework\handle;

use Closure;
use ReflectionClass;
use framework\App;
use framework\handle\Handle;
use framework\handle\router\Job;
use framework\exception\CoreHttpException;

class RouterHandle extends Handle
{
	// 框架实例
	private $app;
	// 配置实例
	private $config;

	// 默认模块
	private $moduleName;
	// 默认控制器
	private $controllerName;
	// 默认动作
	private $actionName;

	// 路由策略
	private $routeStrategy;
	// 请求uri
	private $requestUri;
	// 自定义路由规则
	private $getMap = [];
	private $postMap = [];
	private $putMap = [];
	private $deleteMap = [];

	public function __construct()
	{}

	public function __get($name = '')
	{
		return $this->$name;
	}	
	public function __set($name = '', $value = '')
	{
		$this->$name = $value;
	}

	public function get($uri = '', $func = '')
	{
		$this->getMap[$uri] = $func;
	}

	public function post($uri = '', $func = '')
	{
		$this->postMap[$uri] = $func;
	}

	public function put($uri = '', $func = '')
	{
		$this->putMap[$uri] = $func;
	}

	public function delete($uri = '', $func = '')
	{
		$this->deleteMap[$uri] = $func;
	}

	// 注册路由处理机制
	// App->run 中的方法
	public function register(App $app)
	{
		$app::$container->setSingle('router', $this);

		$request = $app::$container->getSingle('request');

		$this->requestUri = $request->server('REQUEST_URI');
		$this->app = $app;
		$this->config = $app::$container->getSingle('config');

		$this->moduleName = $this->config->config['route']['default_module'];
		$this->controllerName = $this->config->config['route']['default_controller'];
		$this->actionName = $this->config->config['route']['default_action'];

		/**
		 * 路由策略
		 * cli 走定时任务
		 * 网页走pathinfo
		 */
		

		$this->routeStrategy = 'pathinfo';
		if (strpos($this->requestUri, 'index.php') || $app->isCli === 'yes') {
			$this->routeStrategy = 'general';
		}

		// 开启路由
		$this->route();
	}

	// 路由机制
	public function route()
	{
		$strategy = $this->routeStrategy;
		$this->$strategy();

		if ($this->userDefined()) {
			return;
		}

		// 判断模块存在不存在
		if (! in_array(strtolower($this->moduleName), $this->config->config['module'])) {
			throw new CoreHttpException(404, 'Module: ' . $this->moduleName);
		}

		// 获取控制器类
		$controllerName = ucfirst($this->controllerName);
		$controllerPath = "app\\{$this->moduleName}\\controllers\\{$controllerName}";
		
		// app\demo\controllers\Index
		// app/demo/controllers
		// 判断控制器存不存在
		// 命名空间很重要
		if (!class_exists($controllerPath)) {
			
			throw new CoreHttpException(
				404,
				'Controller: ' . $controllerName
			);	
		}

		// 反射解析当前控制器类，判断是有当前的操作方法
		$reflection = new ReflectionClass($controllerPath);
		if (!$reflection->hasMethod($this->actionName)) {
			throw new CoreHttpException(
				404,
				'Action: ' . $this->actionName
			);
		}

		// 实例化当前控制器
		//print_r($controllerPath);die;
		$controller = new $controllerPath();
		
		$actionName = $this->actionName;

		$this->app->responseData = $controller->$actionName();
		
	}

	public function general()
	{
		// 解析参数 
		$app = $this->app;
		$request = $app::$container->getSingle('request');
		$moduleName =  $request->request('module');
		$controllerName = $request->request('controller');
		$actionName = $request->request('action');

		if (!empty($moduleName)) {
			$this->moduleName = $moduleName;
		}
		if (!empty($controllerName)) {
			$this->controllerName = $controllerName;
		}
		if (!empty($actionName)) {
			$this->actionName = $actionName;
		}

		// cli 模式
	}
	public function pathinfo()
	{

	}
	public function userDefined()
	{
		$module = $this->config->config['module'];
		foreach ($module as $v) {
			$routeFile = "{$this->app->rootPath}/config/{$v}/route.php";
			if (file_exists($routeFile)) {
				require($routeFile);
			}
		}

		// 路由匹配
		$uri = "{$this->moduleName}/{$this->controllerName}/{$this->actionName}";
		
		if (!array_key_exists($uri, $this->getMap)) {
			return false;
		}

		// 执行自定义路由匿名函数
		$app = $this->app;
		$request = $app::$controller->getSingle('request');
		$method = $request->method . 'Map';
		if (!isset($this->$method)) {
			throw new CoreHttpException(
				404,
				'Http Method: ' . $request->method
			);	
		}

		// 返回数据这样处理
		$map = $this->$method;
		$this->app->responseData = $map[$uri]($app);

		return true;
	}

	public function microMonomer()
	{

	}
}





















