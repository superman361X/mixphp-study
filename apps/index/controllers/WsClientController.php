<?php

namespace apps\index\controllers;

use mix\facades\Session;
use mix\http\Controller;
use Mix;

class WsClientController extends Controller
{

    public function actionIndex()
    {

        $data = [
            'session' => Session::getSessionId()
        ];
        return $this->render('index',$data);
    }



    public function actionSession(){

        Mix::app()->session->set('userinfo', [
            'uid' => 1008,
            'name' => '小明'
        ]);

        return Session::getSessionId();
        //return Mix::app()->session->getSessionId();
    }

}
