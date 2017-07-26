<?php
/**
 * handle
 */
namespace framework\handle;

use framework\App;
use framework\handle\Handle;

class ConfigHandle extends Handle
{
	private $app;
	private $config;

	public function __construct()
	{}

	// __get
	public function __get($name)
	{
		return $this->$name;
	}
	// __set
	public function __set($name = '', $value = '')
	{
		$this->$name = $value;
	}

	public function register(App $app)
	{
		require $app->rootPath . '/framework/Helper.php';

		$this->app = $app;
		$app::$container->setSingle('config', $this);
		$this->loadConfig($app);

		//设置区时
		date_default_timezone_set($this->config['default_timezone']);
	}

	public function loadConfig(App $app)
	{
		// 加载公共配置
		$defaultCommon = require($app->rootPath . '/config/common.php');
		// 数据库配置
		$databaseCfg = require($app->rootPath . '/config/database.php');
		// nosql配置
		$this->config = array_merge($defaultCommon, $databaseCfg);

		// 加载自定义配置
		// module 哪里拿到的？
		$module = $app::$container->getSingle('config')->config['module'];
		foreach ($module as $v) {
			$file = "{$app->rootPath}/config/{$v}/config.php";
			if (file_exists($file)) {
				$this->config = array_merge($this->config, require($file));
			}
		}
	}
}