<?php

namespace apps\tcpd;

use mix\base\BaseObject;
use mix\helpers\ProcessHelper;


class TcpServer extends BaseObject
{

    // 主机
    public $host;

    // 端口
    public $port;

    // 运行时的各项参数
    public $setting = [];

    // Server对象
    protected $server;

    // 连接事件回调函数
    protected $_onConnectCallback;

    // 接收消息事件回调函数
    protected $_onReceiveCallback;

    // 关闭连接事件回调函数
    protected $_onCloseCallback;


    // 初始化事件
    public function onInitialize()
    {
        parent::onInitialize();
        // 实例化服务器
        $this->server = new \Swoole\Server($this->host, $this->port ,SWOOLE_BASE, SWOOLE_SOCK_TCP);
        //var_dump('*****************************');
        //var_dump($this->server);
        //var_dump('*****************************');
    }

    // 启动服务
    public function start()
    {
        $this->welcome();
        $this->onStart();
        $this->onManagerStart();
        $this->onWorkerStart();
        $this->onConnect();
        $this->onReceive();
        $this->onClose();
        $this->server->set($this->setting);
        $this->server->start();
    }


    // 注册Server的事件回调函数
    public function on($event, $callback)
    {
        switch ($event) {
            case 'Connect':
                $this->_onConnectCallback = $callback;
                break;
            case 'Receive':
                $this->_onReceiveCallback = $callback;
                break;
            case 'Close':
                $this->_onCloseCallback = $callback;
                break;
        }
    }

    // 主进程启动事件
    protected function onStart()
    {
        $this->server->on('Start', function ($server) {
            // 进程命名
            ProcessHelper::setTitle("mix-tcpd: master {$this->host}:{$this->port}");
        });
    }

    // 管理进程启动事件
    protected function onManagerStart()
    {
        $this->server->on('ManagerStart', function ($server) {
            // 进程命名
            ProcessHelper::setTitle("mix-tcpd: manager");
        });
    }

    // 工作进程启动事件
    protected function onWorkerStart()
    {
        $this->server->on('WorkerStart', function ($server, $workerId) {
            // 进程命名
            if ($workerId < $server->setting['worker_num']) {
                ProcessHelper::setTitle("mix-tcpd: worker #{$workerId}");
            } else {
                ProcessHelper::setTitle("mix-tcpd: task #{$workerId}");
            }
        });
    }

    // 请求事件
    protected function onConnect()
    {
        var_dump('111');
        $this->server->on('Connect', function ($request, $response) {
            var_dump('onConnect');
        });
    }


    protected function onReceive()
    {
        $this->server->on('Receive', function ($request, $response) {
            var_dump('onReceive');
        });
    }


    protected function onClose()
    {
        $this->server->on('Close', function ($request, $response) {
            var_dump('onClose');
        });
    }



    //    // 连接事件回调函数
//    public function onConnect(\Swoole\Server $server, $fd)
//    {
//        var_dump('222');
//        echo "connection open: {$fd}\n";
//    }
//
//    // 接收消息事件回调函数
//    public function onReceive(\Swoole\Server $server, $fd, $reactor_id, $data)
//    {
//        $server->send($fd, "Swoole: {$data}");
//        $server->close($fd);
//    }
//
//    // 关闭连接事件回调函数
//    public function onClose(\Swoole\Server $server, $fd)
//    {
//        echo "connection close: {$fd}\n";
//    }




    // 欢迎信息
    protected function welcome()
    {
        $swooleVersion = swoole_version();
        $phpVersion    = PHP_VERSION;
        echo <<<EOL
              __
       ____  / /_  ____
      / __ \/ __ \/ __ \
     / /_/ / / / / /_/ /
    / .___/_/ /_/ .___/
   /_/         /_/


EOL;
        self::send('Server    Name: mix-tcpd');
        self::send("PHP    Version: {$phpVersion}");
        self::send("Swoole Version: {$swooleVersion}");
        self::send("Listen    Addr: {$this->host}");
        self::send("Listen    Port: {$this->port}");
    }

    // 发送至屏幕
    protected static function send($msg)
    {
        $time = date('Y-m-d H:i:s');
        echo "[{$time}] " . $msg . PHP_EOL;
    }

}
