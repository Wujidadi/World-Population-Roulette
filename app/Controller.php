<?php

namespace App;

/**
 * 控制器元類別
 */
abstract class Controller
{
    /**
     * 控制器物件名稱
     *
     * @var string
     */
    protected $_className;

    /**
     * 控制器物件單一實例
     *
     * @var self|null
     */
    protected static $_uniqueInstance;

    /**
     * 取得控制器物件實例
     *
     * @return self
     */
    abstract public static function getInstance();

    /**
     * 建構子
     */
    protected function __construct() {}
}
