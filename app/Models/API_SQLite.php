<?php

namespace App\Models;

use App\SQLiteModel;

/**
 * SQLite 資料庫介面模型
 */
class API_SQLite extends SQLiteModel
{
    protected static $_uniqueInstance = null;

    /** @return self */
    public static function getInstance()
    {
        if (self::$_uniqueInstance == null) self::$_uniqueInstance = new self();
        return self::$_uniqueInstance;
    }

    protected function __construct()
    {
        parent::__construct();
        $this->_className = basename(__FILE__, '.php');
    }

    /**
     * 查詢 SQLite 資料庫
     *
     * @param  string  $sql   SQL 語法
     * @param  array   $bind  綁定變數，可代入一維或二維陣列  
     *                        為一維陣列時，各項以預設的 `PDO::PARAM_STR` 型態綁定  
     *                        為二維陣列時，各項的第一項（`[0]`）為值，第二項（`[1]`）為綁定型態
     * @return array|integer
     */
    public function query(string $sql, array $bind = []): array|int
    {
        return $this->_dbConn->query($sql, $bind);
    }
}
