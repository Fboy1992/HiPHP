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
	// 
	public function select($data = [])
	{
		$field = '';
		$count = count($data);


		switch ($count) {
			case 0:
				$field = '*';
				break;
			case 1:
				if (!isset($data[0])) {
					throw new CoreHttpException(400, 'data format invalid');
				}
				$field = "`{$data[0]}`";
				break;
			default:
				$last = array_pop($data);
				foreach ($data as $val) {
					$field .= "{$val},";
				}
				$field .= $last;
				break;
		}
		$this->sql = "SELECT {$field} FROM `{$this->tableName}`";
	}
	
	// where 语句
	// 
	public function where($data = [])
	{
		if (empty($data)) {
			return;
		}

		$count = count($data);



		// 单条件查询
		if ($count === 1) {
			$field = array_keys($data)[0];
			$value = array_values($data)[0];
			if (! is_array($value)) {
				$this->where = " WHERE `{$field}` = :{$field}";
				$this->params =  $data;
				return $this;
			}
			$this->where = " WHERE `{$field}` {$value[0]} :{$field}";
			$this->params[$field] = $value[1];


			return $this;
		}

		// 多条件查询
		$tmp = $data;
		$last = array_pop($tmp);
		foreach ($data as $k => $v) {
			if ($v === $last) {
				if (!is_array($v)) {
					$this->where .= "`{$k}` = :{$k}";
					$this->params[$k] = $v;
					continue;
				}
				$this->where .= "`{$k}` {$v}[0] :{$k}";
				$this->params[$k] = $v[1];
				continue;
			}
			if (!is_array($v)) {
				$this->where .= " WHERE `{$k}` = :{$k} AND ";
				$this->params[$k] = $v;
				continue;
			}
			$this->where .= " WHERE `{$k}` {$v[0]} :{$k} AND";
			$this->params[$k] = $v[1];
			continue;
		}
		return $this;
	}

	// orderBy
	public function orderBy($sort = '')
	{
		if (!is_string($sort)) {
			throw new CoreHttpException(400, 'argu is not string');
		}
		$this->orderBy = " order by {$sort}";
		return $this;
	}

	// limit
	public function limit($start = 0, $len = 0)
	{
		if (!is_numeric($start) || (is_numeric($len))) {
			throw new CoreHttpException(400, "limit error");
		}
		if ($len === 0) {
			$this->limit = " limit {$start}";
			print_r('adf');die;
			return $this;
		}
		print_r('adfaa');die;
		$this->limit = " limit {$start}, {$len}";
		return $this;
	}

	
}