<?php

namespace App\Handlers;

use PDOException;
use App\Handler;
use Libraries\Logger;
use App\Models\API_SQLite;
use App\Handlers\SourceHandler;
use App\SQLBuilder\AccumulatedDataSQLBuilder;
use App\Exceptions\SourceHandleException;

/**
 * 累計資料處理器
 */
class AccumulationHandler extends Handler
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

    public function createData(int $once = 200): bool
    {
        $functionName = __FUNCTION__;

        try
        {
            $data = SourceHandler::getInstance()->selectAllData();

            if (is_array($data) && count($data) > 0)
            {
                $sql = AccumulatedDataSQLBuilder::getInstance()->buildInsertSQL($data, $once);

                $this->_dbConn->beginTransaction();

                foreach ($sql as $clause)
                {
                    $this->_dbConn->query($clause);
                }

                $this->_dbConn->commit();
            }

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

    public function truncateData(): bool
    {
        $functionName = __FUNCTION__;

        try
        {
            $this->_dbConn->beginTransaction();

            $sql = AccumulatedDataSQLBuilder::getInstance()->buildTruncateSQL();
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

    public function getTotal(): int
    {
        $functionName = __FUNCTION__;

        try
        {
            $total = 0;

            $sql = AccumulatedDataSQLBuilder::getInstance()->buildCountTotalSQL();
            $result = $this->_dbConn->query($sql);
            if (is_array($result) && count($result) > 0)
            {
                $total = (int) $result[0]['Total'];
            }

            return $total;
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

    public function getLocationBySeed(int $seed): ?array
    {
        $functionName = __FUNCTION__;

        try
        {
            $location = null;

            if ($seed >= 0)
            {
                $sql = AccumulatedDataSQLBuilder::getInstance()->buildGetLocationSQL($seed);
                $result = $this->_dbConn->query($sql);
                if (is_array($result) && count($result) > 0)
                {
                    $location = $result[0];
                }
            }

            return $location;
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
