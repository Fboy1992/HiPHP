<?php
/**
 * 异常错误信息码
 */
namespace framework;

class ExceptionCodes
{	
	// -1 ~ -10 基本异常
	public static $baseEcp = ['msg'=>'base_exception', 'code'=>-1];
	// -11 ~ -100 框架异常
	public static $setEcp = ['msg'=>'set_exception', 'code'=>-11];
	public static $getEcp = ['msg'=>'get_exception', 'code'=>-12];
	public static $setSingleEcp = ['msg'=>'setSingle_exception', 'code'=>-13];
	public static $getSingleEcp = ['msg'=>'getSingle_exception', 'code'=>-14];
}
