<?php

chdir(__DIR__);
require_once '../../head.php';

use Libraries\CLI;
use Libraries\Logger;
use App\Models\API_SQLite;

$fileName = basename(__FILE__, '.php');

$tableName = 'WorldPopulationAccumulation';

$dbConn = API_SQLite::getInstance();

$sql = file_get_contents(MIGRATION_DIR . DIRECTORY_SEPARATOR . "Create_Table_{$tableName}.sql");

$dbConn->beginTransaction();

try
{
    $dbConn->query($sql);
    $dbConn->commit();

    echo CLI::colorText("資料表 {$tableName} 建立成功！", CLI_COLOR_SUCCESS, true);
}
catch (Throwable $ex)
{
    $dbConn->rollBack();

    $exType = get_class($ex);
    $exCode = $ex->getCode();
    $exMsg = $ex->getMessage();

    $errMsg = "{$exType} ({$exCode}) {$exMsg}";

    $logMsg = "{$fileName} {$errMsg}";
    Logger::getInstance()->logError($logMsg);

    echo CLI::colorText("資料表 {$tableName} 建立失敗：{$errMsg}", CLI_COLOR_ERROR, true);
}
