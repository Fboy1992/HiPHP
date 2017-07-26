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
		$prefix = App::$container->getSingle('config')
		                         ->config['database']['dbprefix'];
		// get_called_class 返回后期静态绑定的类的名称
		$callClassName = get_called_class();
		$callClassName = explode('\\', $callClassName);
		$callClassName = array_pop($callClassName);

		if (!empty($this->tableName)) {
			if (empty($prefix)) {
				return;
			}
			$this->tableName = $prefix . '_' . $this->tableName;
			return;
		}

		preg_match_all('/([A-Z][a-z]*)/', $callClassName, $match);
		if (!isset($match[1][0]) || empty($match[1][0])) {
			throw new CoreHttpException(401, 'model name invalid');			
		}

		$match = $match[1];
		$count = count($match);
		
		if ($count == 1) {
			$this->tableName = strtolower($match[0]);
			if (empty($prefix)) {
				return;
			}
			$this->tableName = $prefix . '_' . $this->tableName;
			return;
		}
		$last = strtolower(array_pop($match));
		foreach ($match as $v) {
			$this->tableName .= strtolower($v) . '_';
		}

		$this->tableName .= $last;
		if (empty($prefix)) {
			return;
		}
		$this->tableName = $prefix . '_' . $this->tableName;
	}
	
}