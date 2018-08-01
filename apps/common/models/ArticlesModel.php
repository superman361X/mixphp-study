<?php

namespace apps\common\models;

use mix\facades\RDB;

class ArticlesModel
{

    const TABLE = 'articles';

    // 获取一行数据通过id
    public function getRowById($id)
    {
        $sql = "SELECT * FROM `" . self::TABLE . "` WHERE id = :id";
        $row = RDB::createCommand($sql)->bindParams([
            'id' => $id,
        ])->queryOne();
        return $row;
    }

}