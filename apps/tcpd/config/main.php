<?php

// Console应用配置
return [

    // 基础路径
    'basePath'         => dirname(__DIR__) . DIRECTORY_SEPARATOR,

    // 命令命名空间
    'commandNamespace' => 'apps\tcpd\commands',

    // 命令
    'commands'         => [

        'service start'   => ['Service', 'Start'],
        'service stop'    => ['Service', 'Stop'],
        'service restart' => ['Service', 'Restart'],
        'service status'  => ['Service', 'Status'],

    ],

    // 组件配置
    'components'       => [

        // 输入
        'input'  => [
            // 类路径
            'class' => 'mix\console\Input',
        ],

        // 输出
        'output' => [
            // 类路径
            'class' => 'mix\console\Output',
        ],

        // 错误
        'error'  => [
            // 类路径
            'class' => 'mix\console\Error',
            // 错误级别
            'level' => E_ALL,
        ],

        // 日志
        'log'    => [
            // 类路径
            'class'       => 'mix\base\Log',
            // 日志记录级别
            'level'       => ['error', 'info', 'debug'],
            // 日志目录
            'logDir'      => 'logs',
            // 日志轮转类型
            'logRotate'   => mix\base\Log::ROTATE_DAY,
            // 最大文件尺寸
            'maxFileSize' => 0,
            // 换行符
            'newline'     => PHP_EOL,
            // 在写入时加独占锁
            'writeLock'   => false,
        ],

    ],

    // 对象配置
    'objects'          => [

        // WebSocketServer
        'tcpServer' => [

            // 类路径
            'class'   => 'apps\tcpd\TcpServer',
            // 主机
            'host'    => '127.0.0.1',
            // 端口
            'port'    => 9503,

            // 运行时的各项参数：https://wiki.swoole.com/wiki/page/274.html
            'setting' => [
                // 连接处理线程数
                //'reactor_num'   => 8,
                // 工作进程数
                'worker_num'    => 8,
                // 设置 worker 进程的最大任务数
                //'max_request'   => 10000,
                // 数据包分发策略
                //'dispatch_mode' => 2,
                // PID 文件
                'pid_file'      => '/var/run/mix-tcpd.pid',
                // 日志文件路径
                'log_file'      => '/tmp/mix-tcpd.log',
            ],

        ],


    ],

];
