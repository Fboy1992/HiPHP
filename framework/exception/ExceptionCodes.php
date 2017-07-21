<?php
/**
 * 异常错误信息码
 */
namespace framework;

class ExceptionCodes
{	
	// -1 ~ -10 基本异常
	public static $baseEcp = ['msg'=>'基本异常', 'code'=>-1];
	// -11 ~ 100 框架异常
	public static $setEcp = ['msg'=>'set异常', 'code'=>-11];
	public static $getEcp = ['msg'=>'get异常', 'code'=>-12];
	public static $setSingleEcp = ['msg'=>'setSingle异常', 'code'=>-13];

}