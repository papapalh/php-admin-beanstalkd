<?php
// 引入 pda/pheanstalk 类
require_once('vendor/autoload.php');

use Pheanstalk\Pheanstalk;

class My_Beanstalkd
{
    public $beanstalkd;

    private $tube;
    private $job;
    private $priority;
    private $delayed;
    private $again;

    CONST IP = '127.0.0.1';
    CONST PORT = 11300;

    public function __construct()
    {
        $this->beanstalkd = new Pheanstalk(self::IP, self::PORT);
    }

    // 帮助
    public function help() {
        echo sprintf("\e[32m%s\e[0m     %s\n", '--help', '帮助');
        echo sprintf("\e[32m%s\e[0m  %s\n", '--connect', '测试');
        echo sprintf("\e[32m%s\e[0m     %s\n", '--tube', '设置管道');
        echo sprintf("\e[32m%s\e[0m    %s\n", '--stats', '查看状态');
        echo sprintf("\e[32m%s\e[0m     %s\n", '--list', '查看所有管道');
        echo sprintf("\e[32m%s\e[0m      %s\n", '--put', '插入队列');
        echo sprintf("\e[32m%s\e[0m %s\n", '--reserved', '取出队列');
        echo sprintf("\e[32m%s\e[0m     %s\n", '--peek', '根据ID取出队列');
        echo sprintf("\e[32m%s\e[0m %s\n", '--priority', '设置优先级');
        echo sprintf("\e[32m%s\e[0m  %s\n", '--delayed', '设置延时秒数');
        echo sprintf("\e[32m%s\e[0m    %s\n", '--again', '设置超时重发时间');
        echo sprintf("\e[32m%s\e[0m   %s\n", '--delete', '删除任务');
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

    // 设置优先级
    public function priority($priority = 1024) {
        $this->priority = $priority;
    }

    // 设置延时秒数
    public function delayed($delayed = 0) {
        $this->delayed = $delayed;
    }

    // 设置超时重发时间
    public function again($delayed = 0) {
        $this->again = $again;
    }

    // 插入队列
    public function put($content = '') {
        if (!$this->tube) throw new Exception("Please set tube.", 1);
        if (!$content) throw new Exception("Please set content.", 1);

        if (!$this->priority) $this->priority = 1024;
        if (!$this->delayed) $this->delayed = 0;
        if (!$this->again) $this->again = 0;

        $this->beanstalkd->useTube($this->tube);

        $id = $this->beanstalkd->put($content, $this->priority, $this->delayed, $this->again);

        echo "\e[32mJob-Id:$id\e[0m\n";
    }

    // 取出队列
    public function reserved() {
        if (!$this->tube) throw new Exception("Please set tube.", 1);
        // 默认采取不堵塞方式 10s
        $this->job = $this->beanstalkd->watch($this->tube)->ignore('default')->reserve(10);
        print_r($this->job);
    }

    // 根据ID取出队列
    public function peek($id = '') {
        if (!$id) throw new Exception("Please set id.", 1);
        $this->job = $this->beanstalkd->peek($id);
        print_r($this->job);
    }

    // 删除任务
    public function delete() {
        if (!$this->job) throw new Exception("Please set job.", 1);
        $this->beanstalkd->delete($this->job);
        echo "\e[32mdone.\e[0m\n";
    }
}

$my_beanstalkd = new My_Beanstalkd();
