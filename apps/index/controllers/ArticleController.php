<?php

namespace apps\index\controllers;

use apps\index\models\UserForm;
use mix\facades\Request;
use mix\http\Controller;

class ArticleController extends Controller
{

    public function actionDetail()
    {
        app()->response->format = \mix\http\Response::FORMAT_JSON;

        // 使用模型
//        $model             = new UserForm();
//        $model->attributes = Request::get() + Request::post();
//        $model->setScenario('create');
//        if (!$model->validate()) {
//            return ['code' => 1, 'message' => 'FAILED', 'data' => $model->getErrors()];
//        }

        // 执行保存数据库
        // ...

        // 响应
        return ['code' => 0, 'message' => 'OK'];
    }

}
