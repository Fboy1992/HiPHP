<?php
/**
 * HiPHP
 * 
 */
namespace app\demo\models;

use framework\App;
use framework\orm\Model;
use framework\exception\CoreHttpException;

class User extends Model
{
	public function findUserInfoByid($id = 1)
	{
		$where = [
			'id' => ['=', $id],
		];

		$res = $this->where($where)
		            ->orderBy('id asc')->findOne();

		return $res;
	}
}

