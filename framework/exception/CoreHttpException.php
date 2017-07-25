<?php
/**
 * core http exception
 */
namespace framework\exception;

use Exception;

class CoreHttpException extends Exception
{
	protected $code = '';
	protected $message = '';

	private $httpCodes = [
		200 => 'success',
		304 => 'not modified',

		400 => 'bad request',
		403 => 'forbidden',
		404 => 'not found',

		500 => 'server inner error',
		503 => 'service unavailable'
	];

	public function __construct($code = 200, $msg)
	{
		//parent::__construct($msg, $code);
		$this->code = $code;
		if (empty($msg)) {
			$this->message = $this->httpCodes[$this->code];
		} else {
			$this->message = $msg . ' : ' . $this->httpCodes[$this->code];
		}
	}
	// rest 风格http响应
	public function response()
	{
		$data = [
			'__coreError' => [
				'code'    => $this->getCode(),
				'message' => $this->getMessage(),
				'informations' => [
					'file'    => $this->getFile(),
					'line'    => $this->getLine(),
					'trace'   => $this->getTrace(),
				]
			]
		];

		// log
		
		// response
		header('Content-Type:Application/json; Charset=utf-8');
		die(json_encode($data, JSON_UNESCAPED_UNICODE));
	}
	// rest 风格http异常
	public static function responseErr($e)
	{
		$data = [
			'__coreError' => [
				'code' => 500,
				'message' => $e,
				'informations' => [
					'file' => $e['file'],
					'line' => $e['line']
				]
			]
		];

		// log
		// response
		header('Content-Type:Application/json; Charset=utf-8');
		die(json_encode($data, JSON_UNESCAPED_UNICODE));

	}
}
