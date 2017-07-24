<?php
/**
 * 异常处理句柄
 */
namespace framework\handle;

use framework\App;
use framework\handle\Handle;
use framework\exception\CoreHttpException;

class ExceptionHandle extends Handle
{
	private $exception;

	public function __construct()
	{}

	public function register(App $app)
	{
		set_exception_handler([$this, 'exceptionHandler']);
	}

	public function exceptionHandler($exception)
	{
		$exceptionInfo = [
			'code'     => $exception->getCode(),
			'message'  => $exception->getMessage(),
			'file'     => $exception->getFile(),
			'line'     => $exception->getLine(),
			'trace'    => $exception->getTrace(),
			'previous' => $exception->getPrevious()
		];

		CoreHttpException::responseErr($exceptionInfo);
	}
}
