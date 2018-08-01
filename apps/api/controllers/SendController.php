<?php

namespace apps\api\controllers;

use mix\facades\Redis;
use mix\http\Controller;

class SendController extends Controller
{

    // 默认动作
    public function actionSend()
    {
        // 投递任务
        $data = [
            'to' => ['554157247@qq.com' => 'Email Form mixPHP!'],
            'body' => 'Here is the message itself' . date('Y-m-d H:i:s', time()),
            'subject' => 'The title content',
        ];
        Redis::select(1);
        Redis::lpush('queue:email', serialize($data));
        return date('Y-m-d H:i:s', time());
    }


    public function actionSync()
    {

//        // 连接
//        $redis = new \Redis();
//        if (!$redis->connect('127.0.0.1', 6379)) {
//            throw new \Exception('Redis Connect Failure');
//        }
//        $redis->auth('int@1515');
//        $redis->select(0);
//
//        // 从进程消息队列中抢占一条消息
//        $data = $redis->rpop('queue:email');

        Redis::select(1);
        $data = Redis::rpop('queue:email');
        $data = unserialize($data);

        $transport = (new \Swift_SmtpTransport('smtp.163.com', '465', 'ssl'))
            ->setUsername('superman361X@163.com')
            ->setPassword('zaishenzhen2018');
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);
        // Create a message
        $message = (new \Swift_Message($data['subject']))
            ->setFrom(['superman361X@163.com' => 'Nil'])
            ->setTo($data['to'])
            ->setBody($data['body']);
        // Send the message
        return $mailer->send($message);
    }

}
