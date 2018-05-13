<?php

require_once('beanstalkd.php');

// 处理 cli 参数
for ($i=0; $i < count($argv); $i++) {
	if (preg_match('/^--.*/', $argv[$i])) {
		$param = explode('=', str_replace('--', '', $argv[$i]));
		$params[$param[0]] = $param[1] ?? '';
	}
}

if (!@$params) call_user_func(array($my_beanstalkd, 'help'));

// 分发到装饰器
foreach ($params as $action => $p) {
	try {
		if (!method_exists($my_beanstalkd, $action)) throw new Exception("action or param error.", 1);	
		call_user_func(array($my_beanstalkd, $action), $p);
	} catch (Exception $e) {
		echo sprintf("\e[32m%s\e[0m\n", $e->getMessage());
	}
}

