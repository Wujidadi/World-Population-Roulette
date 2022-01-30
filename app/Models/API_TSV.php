<?php

namespace App\Models;

use App\Model;
use Libraries\Logger;
use App\Exceptions\SourceHandleException;

/**
 * TSV 原始資料檔介面模型
 */
class API_TSV extends Model
{
    protected $_tsvSourceFile;

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
        $this->_tsvSourceFile = STORAGE_DIR . DIRECTORY_SEPARATOR . 'tsv' . DIRECTORY_SEPARATOR . 'src.tsv';
    }

    public function getTotal(): int
    {
        $count = 0;

        if (($handle = fopen($this->_tsvSourceFile, 'r')) !== false)
        {
            while (!feof($handle))
            {
                $line = fgets($handle);

                # 不計空行
                if ($line !== false && $line !== '')
                {
                    $count++;
                }
            }

            fclose($handle);
        }

        # 減去標題行
        if ($count > 0)
        {
            $count--;
        }

        return $count;
    }

    public function getSourceData(int $from = 0, int $to = 200): array
    {
        $functionName = __FUNCTION__;

        $data = [];

        $row = 0;

        if ($from < 0 || $to < $from)
        {
            $errMsg = "Wrong from/to row number (from = {$from}, to = {$to})";

            $logMsg = "{$this->_className}::{$functionName} {$errMsg}";
            Logger::getInstance()->logError($logMsg);

            throw new SourceHandleException($errMsg, SourceHandleException::EXCEPTION_CODE['WrongFromTo']);
        }

        # 第 0 行為標題列，故指定的起訖行數須各加 1
        $from += 1;
        $to += 1;

        if (($handle = fopen($this->_tsvSourceFile, 'r')) !== false)
        {
            while ($row < $from)
            {
                fgets($handle);
                $row++;
            }

            while (($line = fgetcsv($handle, null, "\t")) !== false && $row <= $to)
            {
                # 忽略標題行、空行及欄位數目不正確的資料行
                if ($row > 0 && $line !== false && count($line) === 9)
                {
                    $data[] = [
                        'level0'     => $line[0],
                        'level1'     => $line[1],
                        'level2'     => $line[2],
                        'displayed'  => $line[4],
                        'population' => (int) $line[5],
                        'dataDate'   => $line[7],
                        'notes'      => $line[8]
                    ];
                }

                $row++;
            }

            fclose($handle);

            return $data;
        }
        else
        {
            $errMsg = "Fail reading file {$this->_tsvSourceFile})";

            $logMsg = "{$this->_className}::{$functionName} {$errMsg}";
            Logger::getInstance()->logError($logMsg);

            throw new SourceHandleException($errMsg, SourceHandleException::EXCEPTION_CODE['FailReadingFile']);
        }
    }
}
