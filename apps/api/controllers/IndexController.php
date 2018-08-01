<?php

namespace apps\api\controllers;

use mix\console\ExitCode;
use mix\http\Controller;


/**
 * 默认控制器
 * @author 刘健 <coder.liu@qq.com>
 */
class IndexController extends Controller
{

    // 默认动作
    public function actionIndex()
    {

        // 蜕变为守护进程
//        \mix\process\Process::daemon();
        // ...
//        return ExitCode::OK;
        return 'Hello World !';
    }

}
