<?php
/**
 * 请求类
 */
namespace framework;

use framework\App;

class Request
{
	// 请求模块
	private $module = '';
	// 请求控制器
	private $controller = '';
	// 请求动作
	private $action = '';

	// 请求server参数	
	private $serverParams = [];
	// 请求参数
	private $envParams = [];

	// 请求所有参数
	private $requestParams = [];
	// get参数
	private $getParams = [];
	// post参数
	private $postParams = [];

	// 请求方法
	private $method = '';
	// 服务IP
	private $serverIp = '';
	// 客户端IP
	private $clientIp = '';

	// 开始时间
	private $beginTime = 0;
	// 结束时间
	private $endTime = 0;
	// 消耗时间
	private $consumeTime = 0;

	// 请求身份ID
	private $requestId = '';

	//构造方法
	public function __construct(App $app)
	{
		$this->serverParams = $_SERVER;
		$this->method       = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';
		$this->serverIp     = isset($_SERVER['REMOTE_ADDR']) ? strtolower($_SERVER['REMOTE_ADDR']) : '';
		$this->clientIp     = isset($_SERVER['SERVER_ADDR']) ? strtolower($_SERVER['SERVER_ADDR']) : '';
		$this->beginTime    = isset($_SERVER['REQUEST_TIME_FLOAT']) ? $_SERVER['REQUEST_TIME_FLOAT'] : time(true);

		// 无 cli
		$this->requestParams = $_REQUEST;
		$this->getParams     = $_GET;
		$this->postParams    = $_POST;

		// 加载环境参数
		$this->loadEnv($app);
	}

	// 加载环境参数
	private function loadEnv(App $app)
	{
		$env = parse_ini_file($this->rootPath . '/.env', true);
		$this->envParams = array_merge($_ENV, $env);
	}

	// 获取request参数
	public function request($value = '', $default, $checkEmpty = true)
	{
		if (! isset($this->requestParams[$value])) {
			return '';
		}
		if (empty($this->requestParams[$value]) && $checkEmpty) {
			return $default;
		}
		return htmlspecialchars($this->requestParams[$value]);
	}

	// __get
	public function __get($name = '')
	{
		return $this->$name;
	}
	// __set
	public function __set($name = '', $value = '')
	{
		$this->name = $value;
	}

}
