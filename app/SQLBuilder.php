<?php

namespace App;

/**
 * SQL 語法建構器元類別
 */
abstract class SQLBuilder
{
    /**
     * SQL 語法建構器物件名稱
     *
     * @var string
     */
    protected $_className;

    /**
     * SQL 語法建構器物件單一實例
     *
     * @var self|null
     */
    protected static $_uniqueInstance;

    /**
     * 取得 SQL 語法建構器物件實例
     *
     * @return self
     */
    abstract public static function getInstance();

    /**
     * 建構子
     */
    protected function __construct() {}
}
