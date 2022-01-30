<?php

chdir(__DIR__);
require_once '../../head.php';

use Libraries\CLI;
use Libraries\Logger;

$insertSourceDataScript      = CLI_DIR . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Source'      . DIRECTORY_SEPARATOR . 'Insert.php';
$insertAccumulatedDataScript = CLI_DIR . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Accumulated' . DIRECTORY_SEPARATOR . 'Insert.php';

try
{
    `php {$insertSourceDataScript}`;
    `php {$insertAccumulatedDataScript}`;

    echo CLI::colorText('新增資料成功！', CLI_COLOR_SUCCESS, true);
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
