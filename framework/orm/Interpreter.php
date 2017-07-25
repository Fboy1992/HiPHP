<?php
/**
 * sql 解释器
 * 	 解释器用了trait语法
 */
namespace framework\orm;

use framework\exception\CoreHttpException;

trait Interpreter
{
	private $where = '';
	public $params = '';
	private $orderBy = '';
	private $limit = '';
	private $offset = '';
	private $sql = '';

	// 插入一条数据
	public function insert($data = [])
	{
		if (empty($data)) {
			throw new CoreHttpException(400, "argument data is null", 400);
		}

		$fieldString = '';
		$valueString = '';
		$i = 0;
		foreach ($data as $k => $v) {
			if ($i === 0) {
				$fieldString .= "`{$k}`";
				$valueString .= ":{$k}";
				$this->params[$k] = $v;
				++$i;
				continue;
			}

			$fieldString .= " , `{$k}`";
			$valueString .= " , :{$k}";
			$this->params[$k] = $v;
			++$i;
		}
		unset($k);
		unset($v);

		$this->sql = "INSERT INTO `{$this->tableName}` ({$fieldString}) VALUES ($valueString)";
	}

	// 删除
	public function del($data = [])
	{
		$this->sql = "DELETE FROM `{$this->tableName}`";
	}

	// 跟新数据
	// 
	public function updateData($data = [])
	{
		if (empty($data)) {
			throw new CoreHttpException(400, "argument data is null");
		}
		$set = '';
		$dataCopy = $data;
		$pop = array_pop($dataCopy);
		foreach ($$data as $k => $v) {
			if ($v === $pop) {
				$set .= "`{$k}` = :$k";
				$this->params[$k] = $v;
				continue;
			}
		}
		$this->sql = "UPDATE `{$this->tableName}` SET {$set}";
	}

	// 查找一条数据
	
	// where 语句
	// 
	public function where($data = [])
	{
		if (empty($data)) {
			return;
		}

		$count = count($data);
	}
}