<?php

namespace App\Handlers;

use PDOException;
use App\Handler;
use Libraries\Logger;
use App\Models\API_TSV;
use App\Models\API_SQLite;
use App\SQLBuilder\SourceDataSQLBuilder;
use App\Exceptions\SourceHandleException;

/**
 * 原始資料處理器
 */
class SourceHandler extends Handler
{
    protected $_dbConn;

    protected static $_uniqueInstance = null;

    public static function getInstance(): self
    {
        if (self::$_uniqueInstance == null) self::$_uniqueInstance = new self();
        return self::$_uniqueInstance;
    }

    protected function __construct()
    {
        $this->_className = basename(__FILE__, '.php');
        $this->_dbConn = API_SQLite::getInstance();
    }

    public function createDataFromSourceFile(int $once = 200): bool
    {
        $functionName = __FUNCTION__;

        try
        {
            $max = API_TSV::getInstance()->getTotal();

            $this->_dbConn->beginTransaction();

            for ($i = 0; $i < $max; $i += $once)
            {
                $sql = SourceDataSQLBuilder::getInstance()->buildInsertSQL($i, $i + $once - 1);
                // echo $sql . PHP_EOL;
                $this->_dbConn->query($sql);
            }

            $this->_dbConn->commit();

            return true;
        }
        catch (PDOException $ex)
        {
            if ($this->_dbConn->inTransaction())
            {
                $this->_dbConn->rollBack();
            }

            $exCode = $ex->getCode();
            $exMsg = $ex->getMessage();

            $errMsg = "PDOException ({$exCode}) {$exMsg}";

            $logMsg = "{$this->_className}::{$functionName} {$errMsg}";
            Logger::getInstance()->logError($logMsg);

            return false;
        }
    }

    public function countAllData(): int
    {
        $count = 0;

        $sql = SourceDataSQLBuilder::getInstance()->buildCountAllSQL();
        $result = $this->_dbConn->query($sql);
        if (is_array($result) && count($result) > 0)
        {
            $count = (int) $result[0]['Count'];
        }

        return $count;
    }

    public function selectData(int $from = 0, int $to = 200, int $once = 200): array
    {
        $functionName = __FUNCTION__;

        $result = [];

        if ($from < 0 || $to <= $from)
        {
            $errMsg = "Wrong from/to row number (from = {$from}, to = {$to})";

            $logMsg = "{$this->_className}::{$functionName} {$errMsg}";
            Logger::getInstance()->logError($logMsg);

            throw new SourceHandleException($errMsg, SourceHandleException::EXCEPTION_CODE['WrongFromTo']);
        }
        else
        {
            for ($i = $from; $i < $to; $i += $once)
            {
                try
                {
                    $limit = ($to >= $once) ? ($i + $once - 1) : ($to - 1);
                    $sql = SourceDataSQLBuilder::getInstance()->buildSelectSQL($i, $limit);
                    // echo $sql . PHP_EOL;
                    $result = array_merge($result, $this->_dbConn->query($sql));
                }
                catch (PDOException $ex)
                {
                    if ($this->_dbConn->inTransaction())
                    {
                        $this->_dbConn->rollBack();
                    }

                    $exCode = $ex->getCode();
                    $exMsg = $ex->getMessage();

                    $errMsg = "PDOException ({$exCode}) {$exMsg}";

                    $logMsg = "{$this->_className}::{$functionName} {$errMsg}";
                    Logger::getInstance()->logError($logMsg);
                }
            }

            return $result;
        }
    }

    public function selectAllData(int $once = 200): array
    {
        $functionName = __FUNCTION__;

        $result = [];

        $from = 0;
        $to = $this->countAllData();

        for ($i = $from; $i < $to; $i += $once)
        {
            try
            {
                $limit = ($to >= $once) ? ($i + $once - 1) : ($to - 1);
                $sql = SourceDataSQLBuilder::getInstance()->buildSelectSQL($i, $limit);
                // echo $sql . PHP_EOL;
                $result = array_merge($result, $this->_dbConn->query($sql));
            }
            catch (PDOException $ex)
            {
                if ($this->_dbConn->inTransaction())
                {
                    $this->_dbConn->rollBack();
                }

                $exCode = $ex->getCode();
                $exMsg = $ex->getMessage();

                $errMsg = "PDOException ({$exCode}) {$exMsg}";

                $logMsg = "{$this->_className}::{$functionName} {$errMsg}";
                Logger::getInstance()->logError($logMsg);
            }
        }

        return $result;
    }

    public function truncateData(): bool
    {
        $functionName = __FUNCTION__;

        try
        {
            $this->_dbConn->beginTransaction();

            $sql = SourceDataSQLBuilder::getInstance()->buildTruncateSQL();
            // echo $sql . PHP_EOL;
            $this->_dbConn->query($sql);

            $this->_dbConn->commit();

            return true;
        }
        catch (PDOException $ex)
        {
            if ($this->_dbConn->inTransaction())
            {
                $this->_dbConn->rollBack();
            }

            $exCode = $ex->getCode();
            $exMsg = $ex->getMessage();

            $errMsg = "PDOException ({$exCode}) {$exMsg}";

            $logMsg = "{$this->_className}::{$functionName} {$errMsg}";
            Logger::getInstance()->logError($logMsg);

            return false;
        }
    }
}
