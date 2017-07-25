<?php
/**
 * DB 数据库类
 */

namespace framework\orm;

use framework\App;
use framework\exception\CoreHttpException;

class DB
{
	use Interpreter;

	// 数据库类型
	protected $dbtype = '';
	// 表名
	protected $tableName = '';
	// 数据库策略映射
	protected $dbStrategyMap = [
		'mysqldb' => 'framework\orm\Mysql'
	];
	// 数据库实例
	protected $dbInstance;
	// 自增id
	protected $id = '';

	// 走主库的查询语句
	private $master = ['insert', 'update', 'delete'];
	// 当前查询主从
	private $masterslave = '';

	private $dbConfig = [
		'dbhost' => '',
		'dbname' => '',
		'username' => '',
		'password' => '',
	];

	public function __construct()
	{

	}

	// 设置设置表名
	public static function table($tableName = '')
	{
		$db = new self;
		$db->tableName = $tableName;
		$prefix = App::$container->getSingle('config')
								 ->config['database']['dbprefix'];

		if (! empty($prefix)) {
			$db->tableName = $prefix . '_' . $db->tableName;
		}

		// $db->init();
		return $db;
	}

	// 初始化策略
	public function init($masterOrSlave = '')
	{
		$config = App::$container->getSingle('config');
		$this->dbtype = $config->config['database']['dbtype'];

		if (!empty($masterOrSlave)) {
			$this->masterSlave = $masterOrSlave;
		}
		$this->isMasterOrSlave();
		$this->decide();
	}


	// 策略决策
	public function decide()
	{
		$dbStrategyName = $this->dbStrategyMap[$this->dbtype];
		$dbConfig = $this->dbConfig;
		// 注入容器
		$this->dbInstance = App::$container->getSingle(
			"{$this->dbtype}-{$this->masterSlave}",
			function () use ($dbStrategyName, $dbConfig) {
				return new $dbStrategyName(
					$dbConfig['dbhost'],
					$dbConfig['dbname'],
					$dbConfig['username'],
					$dbConfig['password']
				);
			}
		);
	}

	// 判断走主库还是走从库
	public function isMasterOrSlave()
	{
		if (!empty($this->masterSlave)) {
			$this->initMaster();
			return;
		}
		foreach ($this->master as $v) {
			$res = stripos($this->sql, $v);
			if ($res === 0 || $res) {
				$this->initMaster();
				return;
			}
		}
		$this->initSlave();
	}

	// 初始化主库
	public function initMaster()
	{
		$config = App::$container->getSingle('config');
		$dbConfig = $config->config['database'];
		$this->dbConfig['dbhost'] = $dbConfig['dbhost'];
		$this->dbConfig['dbname'] = $dbConfig['dbname'];
		$this->dbConfig['username'] = $dbConfig['username'];
		$this->dbConfig['password'] = $dbConfig['password'];
	}

	// 初始化从库
	public function initSlave()
	{
		$config = App::$container->getSingle('config');
		if (!isset($config->config['database']['slave'])) {
			$this->initMaster();
			return;
		}
		$slave  = $config->config['database']['slave'];
		$randSlave = $slave[array_rand($slave)];
		$dbConfig = $config->config["database-slave-{$randSlave}"];

		$this->dbConfig['dbhost'] = $dbConfig['dbhost'];
		$this->dbConfig['dbname'] = $dbConfig['dbname'];
		$this->dbConfig['username'] = $dbConfig['username'];
		$this->dbConfig['password'] = $dbConfig['password'];

		$this->masterSlave = "slave-{$randSlave}";

	}

	// 查询一条语句
	public function findOne($data = [])
	{
		$this->select($data);
		$this->buildSql();
		$functionName = __FUNCTION__;
		return $this->dbInstance->$functionName($this);
	}

	// 查找所有数据
	public function findAll($data = [])
	{
		$this->select($data);
		$this->buildSql();
		$functionName = __FUNCTION__;
		return $this->dbInstance->functionName($this);
	}

	// 保存
	public function save($data = [])
	{
		$this->insert($data);
		$this->init();
		$functionName = __FUNCTION__;
		return $this->dbInstance->$functionName($this);
	}

	// 删除
	public function delete()
	{
		$this->del();
		$this->buildSql();
		$functionName = __FUNCTION__;
		return $this->dbInstance->$functionName($this);
	}

	// 更新
	public function update($data = [])
	{
		$this->updateData($data);
		$this->buildSql();
		$functionName = __FUNCTION__;
		return $this->dbInstance->$functionName($this);
	}

	// count
	public function count($data = '')
	{
		$this->countColumn($data);
		$this->buildSql();
		return $this->dbInstance->findAll();
	}

	//
	public function sum($data = '')
	{
		$this->sumColumn($data);
		$this->buildSql();
		return $this->dbInstance->query($this);
	}

	//
	public function query($sql = '')
	{
		$this->querySql($sql);
		$this->init();
		return $this->dbInstance->query($this);
	}

	public function buildSql()
	{
		if (!empty($this->where)) {
			$this->sql .= $this->where;
		}
		if (!empty($this->ordreBy)) {
			$this->sql .= $this->orderBy;
		}
		if (!empty($this->limit)) {
			$this->sql .= $this->limit;
		}
		$this->init();
	}

	public static function beginTransaction()
	{
		$instance = App::$container->getSingle('DB', function() {
			return new DB();
		});
		$instance->init('master');
		$instance->dbInstance->beginTransaction();
	}
	public static function commit()
	{
		$instance = App::$container->getSingle('DB', function() {
			return new DB();
		});
		$instance->init('master');
		$instance->dbInstance->commit();
	}
	public static function rollBack()
	{
		$instance = App::$container->setSingle('DB', function() {
			return new DB();
		});
		$instance->init('master');
		$instance->dbInstance->rollBack();
	}

	public function __get($name = '')
	{
		return $this->$name;
	}
	public function __set($name = '', $value = '')
	{
		$this->$name = $value;
	}

}
