<?php
/**
 * log 日志处理句柄
 */
namespace framework\handle;

use framework\App;
use framework\handle\Handle;
use framework\exception\CoreHttpException;

class LogHandle extends Handle
{
	private $logPath;

	// 日志文件
	private $logFileName = 'hi-php-framework-run';

	public function register(App $app)
	{
		$app::$container->setSingle('logger', $this);
	}

	//构造函数
	public function __construct()
	{
		// 日志目录检测
		$this->logPath = env('log_path');
		if (empty($this->logPath) || !isset($this->logPath['path'])) {
			throw new CoreHttpException(404, 'log path is not defined');
		}
		$this->logPath = $this->logPath['path'];
		$this->logPath = App::$app->rootPath . $this->logPath;
		if (! file_exists($this->logPath)) {
			mkdir($this->logPath, 0777, true);
		}

		// 构建日志文件
		$this->logFileName .= '.' . date('Ymd', time());
	}

	// 写日志
	// 
	public function write($data = [])
	{
		// Helper 帮助文件中的方法
		hi_log(
			$data,
			$this->logPath . $this->logFileName
		);
	}
}