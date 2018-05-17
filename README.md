# php-admin-beanstalkd
基于 pda/pheanstalk 管理 beanstalkd 消息队列

##有哪些功能？
* 实现了使用PHP管理beanstalkd的基本功能.
* 使用它可以方便快捷的为 PHP 操作 beanstalkd.

##注意
* `PHP > 7.0`.
* 在`cli`下运行，可设置自己的环境变量，下方实例均在无环境变量的条件.

##快速开始.
* 拉取 Github 项目至本地. git clone `https://github.com/papapalh/php-admin-beanstalkd.git`
* 使用 `composer update` 拉取 `composer` 依赖.
* 修改项目下的`config.php`的配置文件

##演示实例
* 基本
  * 获取帮助信息 `php admin.php --help`
  * 测试连接  `php admin.php --connect`
  * 查看所有管道 `php admin.php --list`
  * 查看 beanstalkd 状态 `php admin.php --stats`


* 维护类-以管道 newUser 为示例
  * 设置管道 `php admin.php --tube=newUser`
  * 查看管道状态 `php admin.php --tube=newUser --stats`
  * 插入管道队列 `php admin.php --tube=newUser --put='This is put'`
  * 取出job `php admin.php --tube=newUser --reserved`
  * 查看job状态 `php admin.php --tube=newUser --reserved --stats`
  * 根据ID取出job `php admin.php --peek=ID`

* 消费者类
  * 设置任务优先级为1000/延时秒数为10s/超时重发时间8s 
    * `php admin.php --tube=newUser --priority=1000 --delayed=10 --again=8 --put='I am job'`
  * 取出队列后删除任务 `php admin.php --tube=newUser --reserved --delete`