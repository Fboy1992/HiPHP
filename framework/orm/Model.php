<?php
/**
 * model 类
 */
namespace framework\orm;

use framework\App;
use framework\orm\DB;
use framework\exception\CoreHttpException;

class Model extends DB
{
	public function __construct()
	{
		parent::__construct();
		$this->getTableName();
	}

	public function getTableName()
	{
		
	}
}