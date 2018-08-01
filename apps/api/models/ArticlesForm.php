<?php

namespace apps\api\models;

use mix\validators\Validator;
use apps\common\models\ArticlesModel;

class ArticlesForm extends Validator
{

    public $id;

    // 规则
    public function rules()
    {
        return [
            'id' => ['integer', 'unsigned' => true, 'maxLength' => 10],
        ];
    }

    // 场景
    public function scenarios()
    {
        return [
            'actionDetails' => ['required' => ['id']],
        ];
    }

    // 获取详情
    public function getDetails()
    {
        return (new ArticlesModel())->getRowById($this->id);
    }

}