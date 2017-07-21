<?php
/**
 * 运行文件
 */
namespace framework;

use framework\Request;
use framework\Response;
use framework\App;

require_once(__DIR__ . '/App.php');

try {
	// 加载
	//echo "load";
	//
	// ---------- init ---------- //
	$app = new App(ROOT_PATH, function() {
		return require( __DIR__ . '/Load.php');
	});

	// ---------- loading handle module ---------- //
	// $app->load(function () {
	//	return new Response();
	// });
	// 
	// 
	// 
	// ---------- start app ---------- //
	$app->run(function() use ($app) {
		return new Request($app);
	});
	
	// ---------- stop app and response ---------- //
	$app->response(function() {
		return new Response(); 
	});
	
} catch (Exception $e) {
	echo $e->getMessage();
}