<?php

namespace apps\api\controllers;

use mix\facades\Token;
use mix\http\Controller;

class TokenController extends Controller
{

    public function actionToken()
    {
        /* 验证账号密码成功后 */

        // 创建 tokenId
        Token::createTokenId();
        // 保存会话信息
        $userinfo = [
            'uid' => 1088,
            'openid' => 'yZmFiZDc5MjIzZDMz',
            'username' => '小明',
        ];
        Token::set('userinfo', $userinfo);
        // 设置唯一索引
        Token::setUniqueIndex($userinfo['openid']);
        // 响应
        return $this->toJson([
            'errcode' => 0,
            'access_token' => Token::getTokenId(),
            'expires_in' => app()->token->expires,
            'openid' => $userinfo['openid'],
        ]);
    }


    public function actionHandle()
    {
        //需要获取access_token get|post传参
        $userinfo = Token::get('userinfo');
        if (empty($userinfo)) {
            // 返回错误码
            return $this->toJson([
                'errcode' => 300000,
                'errmsg' => 'Permission denied'
            ]);
        }

        return $this->toJson($userinfo);
    }

}
