<?php
/**
 * 
 */

namespace app\demo\controllers;

class Index
{
	public function __construct()
	{
		echo __CLASS__;
	}

	public function hello()
	{
		return [
			'code'=>'11',
			'msg'=>'yes'
		];
	}
}