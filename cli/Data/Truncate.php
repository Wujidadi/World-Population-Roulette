<?php

chdir(__DIR__);
require_once '../../head.php';

use Libraries\CLI;
use Libraries\Logger;

$truncateSourceDataScript      = CLI_DIR . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Source'      . DIRECTORY_SEPARATOR . 'Truncate.php';
$truncateAccumulatedDataScript = CLI_DIR . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Accumulated' . DIRECTORY_SEPARATOR . 'Truncate.php';

try
{
    `php {$truncateSourceDataScript}`;
    `php {$truncateAccumulatedDataScript}`;

    echo CLI::colorText('清除資料成功！', CLI_COLOR_SUCCESS, true);
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
