<?php
/**
 * 
 */

namespace app\demo\controllers;

class Index
{
	public function __construct()
	{
		
	}

	public function hello()
	{
		return [
			'code'=>'11',
			'msg'=>'yes'
		];
	}

	public function test()
	{
		return ['test'=>'first'];
	}
}