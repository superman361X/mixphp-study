<?php

namespace apps\api\controllers;

use apps\api\models\Guid;
use mix\facades\Token;
use mix\http\Controller;

class GuidController extends Controller
{

    public function actionGenerate()
    {
        // 创建 tokenId
        Guid::createGuid();
        // 设置唯一索引
        Token::setUniqueIndex();
        // 响应
        return $this->toJson([
            'errcode' => 0,
            'access_token' => Token::getTokenId(),
            'expires_in' => app()->token->expires,
            'openid' => $userinfo['openid'],
        ]);
    }

}
