<?php
/**
 * 错误处理局部
 */
namespace framework\handle;

use framework\exception\CoreHttpException;
use framework\handle\Handle;
use framework\App;

class ErrorHandle extends Handle
{
	private $error;

	public function __construct()
	{}

	public function register(App $app)
	{
		// 设置一个用户定义的错误处理函数
		set_error_handler([$this, 'errorHandler']);
		// 注册一个函数在执行关闭阶段
		register_shutdown_function([$this, 'shutdown']);
	}

	// 脚本结束
	public function shutdown()
	{
		$error = error_get_last();
		if (empty($error)) {
			return;
		}
		$errorInfo = [
			'type'    => $error['type'],
			'message' => $error['message'],
			'file'    => $error['file'],
			'line'    => $error['line']
		];
		CoreHttpException::responseErr($errorInfo);
	}

	// 错误捕获
	public function errorHandler(
		$errorNumber,
		$errorMessage,
		$errorFile,
		$errorLine,
		$errorContext
	) {
		$errorInfo = [
			'type'    => $errorNumber,
			'message' => $errorMessage,
			'file'    => $errorFile,
			'line'    => $errorLine,
			'context' => $errorContext
		];
		CoreHttpException::responseErr($errorInfo);
	}
}