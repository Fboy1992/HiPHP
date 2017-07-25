<?php
/**
 * 运行文件
 */
namespace framework;

use framework\App;
use framework\Request;
use framework\Response;
use framework\handle\ConfigHandle;
use framework\handle\RouterHandle;
use framework\handle\LogHandle;
use framework\handle\ErrorHandle;
use framework\handle\ExceptionHandle;
use framework\handle\NosqlHandle;
use framework\handle\UserDefinedHandle;
use framework\exception\CoreHttpException;

require_once(__DIR__ . '/App.php');

try {
	// 加载
	//echo "load";
	//
	//
	//
	// ---------- init ---------- //
	$app = new App(ROOT_PATH, function() {
		return require( __DIR__ . '/Load.php');
	});

	// ---------- loading handle module ---------- //
	
	// config 
	$app->load(function () {
		return new ConfigHandle();
	});

	// router
	$app->load(function () {
		return new RouterHandle();
	});

	// log
	$app->load(function () {
		return new LogHandle();
	});
	
	// error
	$app->load(function () {
		return new ErrorHandle();
		
	});
	 
	// exception
	$app->load(function () {
		return new ExceptionHandle();
	});
	// 

	// nosql
	$app->load(function () {
		return new NosqlHandle();
	});
	// userDefined
	$app->load(function() {
		return new UserDefinedHandle();
	});



	// ---------- start app ---------- //
	$app->run(function() use ($app) {
		return new Request($app);
	});
	
	// ---------- stop app and response ---------- //
	$app->response(function() {
		return new Response(); 
	});
	
} catch (CoreHttpException $e) {
	// 捕获自定义的异常
	$e->response();
}