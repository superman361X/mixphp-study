<?php

namespace apps\api\controllers;

use mix\facades\Request;
use apps\api\messages\ErrorCode;
use apps\api\models\ArticlesForm;
use mix\http\Controller;

class ArticlesController extends Controller
{

    public function actionDetails()
    {
        // 使用模型
        $model = new ArticlesForm();
        $model->attributes = Request::get();
        $model->setScenario('actionDetails');
        if (!$model->validate()) {
            return ['code' => ErrorCode::INVALID_PARAM];
        }
        // 获取数据
        $data = $model->getDetails();
        if (!$data) {
            return ['code' => ErrorCode::ERROR_ID_UNFOUND];
        }
        // 响应
        return [
            'code' => ErrorCode::SUCCESS,
            'data' => $data
        ];
    }

}