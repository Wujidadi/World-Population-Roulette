<?php

namespace App;

use App\Model;
use Libraries\SQLiteAPI;
use Libraries\Logger;

/**
 * SQLite 資料模型元類別
 */
abstract class SQLiteModel extends Model
{
    /**
     * 資料庫連接實體
     *
     * @var SQLiteAPI
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
    protected function __construct()
    {
        parent::__construct();
        $this->_dbConn = SQLiteAPI::getInstance();
    }

    /**
     * 開啟事務模式（關閉自動提交模式）
     *
     * @return boolean
     */
    public function beginTransaction(): bool
    {
        return !$this->_dbConn->inTransaction() ? $this->_dbConn->beginTransaction() : false;
    }

    /**
     * 提交查詢，開啟事務模式（關閉自動提交模式）時有效  
     * 呼叫後將恢復自動提交模式
     *
     * @return boolean
     */
    public function commit(): bool
    {
        return $this->_dbConn->commit();
    }

    /**
     * 回滾當前事務  
     * 呼叫後將恢復自動提交模式
     *
     * @return boolean
     */
    public function rollBack(): bool
    {
        return $this->_dbConn->rollBack();
    }

    /**
     * 返回當前連線是否處於事務模式（即非自動提交模式）
     *
     * @return boolean
     */
    public function inTransaction(): bool
    {
        return $this->_dbConn->inTransaction();
    }

    /**
     * 記錄查詢語法日誌
     *
     * @param  string   $query       SQL 語句
     * @param  array    $param       Bind 參數
     * @param  string   $entryPoint  呼叫本方法的物件及函數名稱
     * @param  boolean  $compress    是否壓縮輸出 SQL 語句及 Bind 參數，預設為 `true`
     * @return void
     */
    protected function _logQuery(string $query, array $param, string $entryPoint, bool $compress = true): void
    {
        if ($compress)
        {
            $sql = preg_replace('/\n */', ' ', $query);
            $bind = json_encode($param, 320);
        }
        else
        {
            $sql = $query;
            $bind = json_encode($param, 448);
        }
        $logMsg = "{$entryPoint} Query detail:\nSQL: {$sql}\nBind: {$bind}";
        Logger::getInstance()->logInfo($logMsg);
    }
}
