<?php
// 引入 pda/pheanstalk 类
require_once('vendor/autoload.php');

use Pheanstalk\Pheanstalk;

class My_Beanstalkd
{
	public $beanstalkd;

	private $tube;
	private $job;

	CONST IP = '127.0.0.1';
	CONST PORT = 11300;

	function __construct()
	{
		$this->beanstalkd = new Pheanstalk(self::IP, self::PORT);
	}

	// 帮助
	public function help() {
		echo sprintf("\e[32m%s\e[0m     %s\n", '--help', '帮助');
		echo sprintf("\e[32m%s\e[0m  %s\n", '--connect', '测试');
		echo sprintf("\e[32m%s\e[0m     %s\n", '--tube', '设置管道');
		echo sprintf("\e[32m%s\e[0m    %s\n", '--stats', '查看状态');
		echo sprintf("\e[32m%s\e[0m    %s\n", '--list', '查看所有管道');
		echo sprintf("\e[32m%s\e[0m    %s\n", '--put', '插入队列');
		echo sprintf("\e[32m%s\e[0m    %s\n", '--reserved', '取出队列');
		echo sprintf("\e[32m%s\e[0m    %s\n", '--peek', '根据ID取出队列');
		exit;

	}

	// 测试连接
	public function connect() {

		if ($this->beanstalkd->getConnection()->isServiceListening()) {
			echo "\e[32mdone.\e[0m\n";
			return;
		}

		echo "\e[32mfail.\e[0m\n";
	}

	// 当前状态
	public function stats() {

		if ($this->job) {
			print_r($this->beanstalkd->statsJob($this->job));
		}
		elseif ($this->tube) {
			print_r($this->beanstalkd->statsTube($this->tube));
		}
		else {
			print_r($this->beanstalkd->stats());
		}
	}

	// 查看所有管道
	public function list() {
		print_r($this->beanstalkd->listTubes());
	}

	// 设置管道
	public function tube($tube = '') {
		if (!$tube) throw new Exception("Please set tube.", 1);
		$this->tube = $tube;
	}

	// 插入队列
	public function put($content = '') {
		if (!$this->tube) throw new Exception("Please set tube.", 1);
		if (!$content) throw new Exception("Please set content.", 1);

		$this->beanstalkd->useTube($this->tube)->put($content);
		echo "\e[32mdone.\e[0m\n";
	}

	// 取出队列
	public function reserved() {
		if (!$this->tube) throw new Exception("Please set tube.", 1);
		$this->job = $this->beanstalkd->watch($this->tube)->reserve();
		print_r($this->job);
	}

	// 根据ID取出队列
	public function peek($id = '') {
		if (!$id) throw new Exception("Please set id.", 1);
		$this->job = $this->beanstalkd->peek($id);
		print_r($this->job);
	}


}

$my_beanstalkd = new My_Beanstalkd();
