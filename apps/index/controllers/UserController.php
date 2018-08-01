<?php

namespace apps\index\controllers;

use apps\index\models\Container;
use apps\index\models\PaypalLogin;
use apps\index\models\UserForm;
use mix\facades\Redis;
use mix\facades\Request;
use mix\http\Controller;

class UserController extends Controller
{

    public function actionCreate()
    {
        app()->response->format = \mix\http\Response::FORMAT_JSON;

        // 使用模型
        $model = new UserForm();
        $model->attributes = Request::get() + Request::post();
        $model->setScenario('create');
        if (!$model->validate()) {
            return ['code' => 1, 'message' => 'FAILED', 'data' => $model->getErrors()];
        }

        // 执行保存数据库
        // ...

        // 响应
        return ['code' => 0, 'message' => 'OK'];
    }


    public function actionObject(){

        //匿名类
        return (new class{
            public function get(){
                return 'get';
            }
        })->get();
    }


    public function actionRedis(){
        echo 'redis';
//        Redis::select(14);
//        try{
//            while (1){
//                $key = random_int(1,9999999);
//                Redis::setex($key, 10, $key);
//            }
//        }catch (\Exception $exception){
//
//        }


    }
}
