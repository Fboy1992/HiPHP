<?php
/**
 * 用户自定义路由
 */
use framework\App;

$this->get('v1/user/info', function (App $app) {
	return 'hello get router';
});