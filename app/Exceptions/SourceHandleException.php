<?php

namespace App\Exceptions;

use Exception;

/**
 * 原始資料處理例外類別
 */
class SourceHandleException extends Exception
{
    const EXCEPTION_CODE = [
        'FailReadingFile' => 1,
        'WrongFromTo'     => 2
    ];

    protected $message;
    protected $code;

    /**
     * 建構子
     *
     * @param  string|null   $message  例外訊息
     * @param  string|float  $code     例外代碼
     */
    public function __construct(?string $message = null, string|float $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
