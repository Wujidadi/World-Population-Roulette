<?php

chdir(__DIR__);
require_once '../../../head.php';

use Libraries\CLI;
use Libraries\Logger;
use App\Handlers\AccumulationHandler;

$scriptName = 'TruncateAccumulatedData';

try
{
    if (AccumulationHandler::getInstance()->truncateData() === true)
    {
        echo CLI::colorText('清除資料成功！', CLI_COLOR_SUCCESS, true);
    }
    else
    {
        echo CLI::colorText('清除資料失敗！', CLI_COLOR_ERROR, true);
    }
}
catch (Throwable $ex)
{
    $exType = get_class($ex);
    $exCode = $ex->getCode();
    $exMsg = $ex->getMessage();

    $errMsg = "{$exType} ({$exCode}) {$exMsg}";
    echo CLI::colorText($errMsg, CLI_COLOR_ERROR, true);

    $logMsg = "{$scriptName} {$errMsg}";
    Logger::getInstance()->logError($logMsg);
}
