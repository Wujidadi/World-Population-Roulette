<?php

namespace App\SQLBuilder;

use App\SQLBuilder;

/**
 * 累計資料 SQL 語法建構器
 */
class AccumulatedDataSQLBuilder extends SQLBuilder
{
    protected $_tableName = 'WorldPopulationAccumulation';

    protected static $_uniqueInstance = null;

    public static function getInstance(): self
    {
        if (self::$_uniqueInstance == null) self::$_uniqueInstance = new self();
        return self::$_uniqueInstance;
    }

    protected function __construct()
    {
        $this->_className = basename(__FILE__, '.php');
    }

    public function buildInsertSQL(array $data, int $once = 200): array
    {
        $sql = [];

        $k = -1;

        $population = 0;

        for ($i = 0; $i < count($data); $i++)
        {
            if (isset($data[$i]))
            {
                if ($i % $once === 0)
                {
                    if (isset($sql[$k]))
                    {
                        $sql[$k] = preg_replace('/, $/', '; ', $sql[$k]);
                    }

                    $sql[++$k] = "INSERT INTO {$this->_tableName} (Level0, Level1, Level2, Displayed, Population, DataDate, Notes) VALUES ";
                }

                $level0      = $data[$i]['Level0'];
                $level1      = $data[$i]['Level1'];
                $level2      = $data[$i]['Level2'];
                $displayed   = $data[$i]['Displayed'];
                $population += $data[$i]['Population'];
                $dataDate    = $data[$i]['DataDate'];
                $notes       = $data[$i]['Notes'];

                $sql[$k] .= "('{$level0}', '{$level1}', '{$level2}', '{$displayed}', {$population}, '{$dataDate}', '{$notes}'), ";
            }
            else
            {
                break;
            }
        }

        if (isset($sql[$k]))
        {
            $sql[$k] = preg_replace('/, $/', '; ', $sql[$k]);
        }

        return $sql;
    }

    public function buildCountTotalSQL(): string
    {
        $sql = "SELECT MAX(Population) AS Total FROM {$this->_tableName};";
        return $sql;
    }

    public function buildGetLocationSQL(int $seed): string
    {
        $sql = "SELECT * FROM {$this->_tableName} WHERE Population > {$seed} LIMIT 1;";
        return $sql;
    }

    public function buildTruncateSQL(): string
    {
        $sql = "DELETE FROM {$this->_tableName}; DELETE FROM SQLITE_SEQUENCE WHERE name = '{$this->_tableName}';";
        return $sql;
    }
}
