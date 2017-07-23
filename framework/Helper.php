<?php
/**
 * 帮助类
 * 一些公用的函数而已
 */
use framework\App;


function env($paramName = '')
{
	return App::$container->getSingle('request')->env($paramName);
}

function dump($data = [])
{
	ob_start();
	var_dump($data);
	$output = ob_get_clean();
	if (!extension_loaded('xdebug')) {
		$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
		$output = '<pre>' . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
	}
	echo ($output);
	return;
}

function hi_log($data, $fileName = 'debug')
{
	$time = date('Y-m-d H:i:s', time());
	error_log(
		"[{$time}]: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n",
		3,
		$fileName . '.log'
	);
}

