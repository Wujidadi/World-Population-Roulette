<?php

namespace App\SQLBuilder;

use App\SQLBuilder;
use App\Models\API_TSV;

/**
 * 原始資料 SQL 語法建構器
 */
class SourceDataSQLBuilder extends SQLBuilder
{
    protected $_tableName = 'WorldPopulation';

    protected $_tsvSourceFile;

    protected static $_uniqueInstance = null;

    public static function getInstance(): self
    {
        if (self::$_uniqueInstance == null) self::$_uniqueInstance = new self();
        return self::$_uniqueInstance;
    }

    protected function __construct()
    {
        $this->_className = basename(__FILE__, '.php');
        $this->_tsvSourceFile = STORAGE_DIR . DIRECTORY_SEPARATOR . 'tsv' . DIRECTORY_SEPARATOR . 'src.tsv';
    }

    public function buildInsertSQL(int $from = 0, int $to = 200): string
    {
        $sql = '';

        $data = API_TSV::getInstance()->getSourceData($from, $to);
        if (is_array($data) && count($data) > 0)
        {
            $sql = "INSERT INTO {$this->_tableName} (Level0, Level1, Level2, Displayed, Population, DataDate, Notes) VALUES ";

            foreach ($data as $datum)
            {
                extract($datum);
                $sql .= "('{$level0}', '{$level1}', '{$level2}', '{$displayed}', {$population}, '{$dataDate}', '{$notes}'), ";
            }

            $sql = preg_replace('/, $/', ';', $sql);
        }

        return $sql;
    }

    public function buildCountAllSQL(): string
    {
        $sql = "SELECT COUNT(*) AS Count FROM {$this->_tableName};";
        return $sql;
    }

    public function buildSelectSQL(int $from = 0, int $to = 200): string
    {
        $limit = $to - $from + 1;
        $sql = "SELECT * FROM {$this->_tableName} LIMIT {$limit} OFFSET {$from};";
        return $sql;
    }

    public function buildTruncateSQL(): string
    {
        $sql = "DELETE FROM {$this->_tableName}; DELETE FROM SQLITE_SEQUENCE WHERE name = '{$this->_tableName}';";
        return $sql;
    }
}
