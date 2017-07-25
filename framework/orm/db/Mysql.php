<?php
/**
 * mysql 驱动
 */
namespace framework\orm\db;

use framework\orm\DB;
use framework\exception\CoreHttpException;
use PDO;

class Mysql
{
	// db host
	private $dbhost = '';
	// db name
	private $dbname = '';
	// db connect info
	private $dns = '';
	// db username
	private $username = '';
	// db password
	private $password = '';
	// pdo instance
	private $pdo = '';
	// 预处理实例
	private $pdoStatement = '';

	// init mysql driver by pdo
	public function __construct(
		$dbhost = '',
		$dbname = '',
		$username = '',
		$password = ''
	) {
		$this->dbhost = $dbhost;
		$this->dbname = $dbname;
		$this->dsn    = "mysql:dbname{$this->dbname};host={$this->dbhost};";
		$this->username = $username;
		$this->password = $password;

		$this->connect();
	}

	// build connect with mysql by pdo drive
	private function connect()
	{
		$this->pdo = new PDO(
			$this->dsn,
			$this->username,
			$this->password
		);
	}

	// __get
	public function __get($name = '')
	{
		return $this->$name;
	}
	// __set
	public function __set($name = '', $value = '')
	{	
		$this->$name = $value;
	}

	//
	public function findOne(DB $db)
	{
		$this->pdoStatement = $this->pdo->prepare($db->sql);
		$this->bindValue($db);
		$this->pdoStatement->execute();

		return $this->pdoStatement->fetch(PDO::FETCH_ASSOC);
	}

	//
	public function findAll(DB $db)
	{
		$this->pdoStatement = $this->pdo->prepare($db->sql);
		$this->bindValue($db);
		$this->pdoStatement->execute();

		return $this->pdoStatement->fetchAll(PDO::FETCH_ASSOC);
	}

	//
	public function save(DB $db)
	{
		$this->pdoStatement = $this->pdo->prepare($db->sql);
		$this->bindValue($db);
		$res = $this->pdoStatement->execute();

		if (!$res) {
			return false;
		}
		return $db->id = $this->pdo->lastInsertId();
	}

	//
	public function delete(DB $db)
	{
		$this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        $this->pdoStatement->execute();
        return $this->pdoStatement->rowCount();
	}

	//
	public function update(DB $db)
	{
		$this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        return $this->pdoStatement->execute();
	}

	// 
	public function query(DB $db)
	{
		$res = [];
		foreach ($this->pdo->query($db->sql, PDO::FETCH_ASSOC) as $v) {
			$res[] = $v;
		}
		return $res;
	}

	//
	public function bindValue(DB $db)
	{
		if (empty($db->params)) {
			return;
		}
		foreach ($db->params as $k => $v) {
			$this->pdoStatement->bindValue(":{$k}", $v);
		}
	}

	public function beginTransaction()
	{
		$this->pdo->beginTransaction();
	}

	public function commit()
	{
		$this->pdo->commit();
	}

	public function rollBack()
	{
		$this->pdo->rollBack();
	}
}
















