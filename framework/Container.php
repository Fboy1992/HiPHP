<?php
/**
 * 依赖注入容器
 */
namespace framework;

use Exception;
use framework\exception\ExceptionCodes;

class Container
{
	// 类映射
	private $classMap = [];
	// 类实例映射
	public $instanceMap = [];

	// 注册一个类
	public function set($alais = '', $objectName = '')
	{
		// 空值判断
		if (empty($alais) || empty($objectName)) {
			throw new Exception(
				ExceptionCodes::$setEcp['msg'],
				ExceptionCodes::$setEcp['code']
			);
		}

		$this->classMap[$alais] = $objectName;
		if (is_callable($objectName)) {
			return $objectName();
		}
		return new $objectName;
	}
	// 获取一个类
	public function get($alais = '')
	{
		if (array_key_exists($alais, $this->classMap)) {
			if (is_callable($this->classMap[$alais])) {
				return $this->classMap[$alais]();
			}
			return $this->classMap[$alais];
		}
		throw new Exception(
			ExceptionCodes::$getEcp['msg'],
			ExceptionCodes::$getEcp['code']
		);
	}
	// 注册一个示例类
	public function setSingle($alais = '', $object = '')
	{
		if (is_callable($alais)) {
			$instance = $alais();
			$className = get_class($instance);
			$this->instanceMap[$className] = $instance;
			return $instance;
		}
		if (is_callable($object)) {
			if (empty($alais)) {
				throw new Exception(
					ExceptionCodes::$setSingle['msg'],
					ExceptionCodes::$setSingle['code']
				);
			}
			if (array_key_exists($alais, $this->instanceMap)) {
				return $this->instanceMap[$alais];
			}
			// 
			$this->instanceMap[$alais] = $object();
			return $this->instanceMap[$alais];
		}
		if (is_object($alais)) {
			$className = get_class($alais);
			if (array_key_exists($className, $this->instanceMap)) {
				return $this->instanceMap[$className];
			}
			$this->instanceMap[$className] = $alais;
			return $this->instanceMap[$className];
		}
		if (is_object($object)) {
			if (empty($alais)) {
				throw new Exception(
					ExceptionCodes::$setSingle['msg'],
					ExceptionCodes::$setSingle['code']
				);
			}
			$this->instanceMap[$alais] = $object;
			return $this->instanceMap[$alais]; 
		}
		if (empty($alais) && empty($object)) {
			throw new Exception(
				ExceptionCodes::$setSingle['msg'],
				ExceptionCodes::$setSingle['code']
			);
		}

		$this->instanceMap[$alais] = new $alais();
		return $this->instanceMap[$alais];
	}
	// 获取一个实例类
	public function getSingle($alais = '', $closure = '')
	{
		if (array_key_exists($alais, $this->instanceMap)) {
			$instance = $this->instanceMap[$alais];
			if (is_callable($instance)) {
				return $this->instanceMap[$alais] = instance();
			}
			return $instance;
		}

		if (is_callable($closure)) {
			return $this->instanceMap[$alais] = $closure();
		}

		throw new Exception(
			ExceptionCodes::$getSingle['msg'],
			ExceptionCodes::$getSingle['code']
		);
	}
}
