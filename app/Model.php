<?php

namespace App;

use Libraries\Logger;

/**
 * 資料模型元類別
 */
abstract class Model
{
    /**
     * 資料模型物件名稱
     *
     * @var string
     */
    protected $_className;

    /**
     * 資料庫連接實體
     */
    protected $_dbConn;

    /**
     * 資料模型物件單一實例
     *
     * @var self|null
     */
    protected static $_uniqueInstance;

    /**
     * 取得資料模型物件實例
     *
     * @return self
     */
    abstract public static function getInstance();

    /**
     * 建構子
     */
    protected function __construct() {}
}
