<?php
/**
 * handle抽象类
 */

namespace framework\handle;

use framework\App;

abstract class Handle
{
	abstract public function register(App $app);
}
