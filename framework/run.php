<?php
/**
 * 运行文件
 */
namespace framework;

use framework\Request;
use framework\Response;

require_once(__DIR__ . '/App.php');

try {
	// 加载
	echo "load";
} catch (Exception $e) {
	echo $e->getMessage();
}