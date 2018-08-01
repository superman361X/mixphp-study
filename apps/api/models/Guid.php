<?php

namespace apps\api\models;


class Guid
{

    // 保存处理者
    public $saveHandler;

    // 保存的Key前缀
    public $saveKeyPrefix = 'GUID';

    // 有效期
    public $expires = 30*24*3600;

    // guid名
    public $name = 'guid';

    // TokenKey
    protected $_tokenKey;

    // Token前缀
    protected $_tokenPrefix;

    // 唯一索引前缀
    protected $_uniqueIndexPrefix;

    public function __construct()
    {
    }

    // 创建TokenID
    public function createGuid()
    {
        $this->_Guid  = self::getRandomString(32);
        $this->_tokenKey = $this->_tokenPrefix . $this->_Guid;
    }

    // 设置唯一索引
    public function setUniqueIndex($uniqueId)
    {
        $uniqueKey = $this->_uniqueIndexPrefix . $uniqueId;
        // 删除旧token数据
        $beforeTokenId = $this->saveHandler->get($uniqueKey);
        if (!empty($beforeTokenId)) {
            $beforeTokenkey = $this->_tokenPrefix . $beforeTokenId;
            $this->saveHandler->del($beforeTokenkey);
        }
        // 更新唯一索引
        $this->saveHandler->setex($uniqueKey, $this->expires, $this->_tokenId);
    }

    // 赋值
    public function set($name, $value)
    {
        $success = $this->saveHandler->hmset($this->_tokenKey, [$name => serialize($value)]);
        $this->saveHandler->expire($this->_tokenKey, $this->expires);
        return $success ? true : false;
    }

    // 取值
    public function get($name = null)
    {
        if (is_null($name)) {
            $result = $this->saveHandler->hgetall($this->_tokenKey);
            unset($result['__uidx__']);
            foreach ($result as $key => $item) {
                $result[$key] = unserialize($item);
            }
            return $result ?: [];
        }
        $value = $this->saveHandler->hget($this->_tokenKey, $name);
        return $value === false ? null : unserialize($value);
    }

    // 判断是否存在
    public function has($name)
    {
        $exist = $this->saveHandler->hexists($this->_tokenKey, $name);
        return $exist ? true : false;
    }



    public static function getRandomString($length)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $last = 61;
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars{uniqid(mt_rand(0, $last), true)};
        }

        $str .= (strtotime(date('YmdHis', time()))) . substr(microtime(), 2, 6) . sprintf('%03d', rand(0, 999));
        return md5($str);
    }
}
