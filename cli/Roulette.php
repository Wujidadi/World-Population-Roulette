<?php

chdir(__DIR__);
require_once '../head.php';

use Libraries\CLI;
use App\Handlers\AccumulationHandler;

$min = 0;
$max = AccumulationHandler::getInstance()->getTotal() - 1;

$option = getopt('', ['round:', 'output', 'no-tsv']);

$round  = (isset($option['round']) && (int) $option['round'] > 0) ? (int) ($option['round']) : 1;
$output = isset($option['output']);
$noTsv  = isset($option['no-tsv']);

$tsvContent = '';

for ($i = 0; $i < $round; $i++)
{
    $seed = mt_rand($min, $max);

    $data = AccumulationHandler::getInstance()->getLocationBySeed($seed);

    $serial = number_format($seed + 1);

    if ($output)
    {
        echo '第 ';
        echo CLI::colorText($serial, CLI_COLOR_SERIAL);
        echo ' 人在';
        echo CLI::colorText($data['Displayed'], CLI_COLOR_LOCATION, true);
    }

    if (!$noTsv)
    {
        $tsvContent .= "{$seed}\t{$data['Displayed']}\n";
    }
}

if (!$noTsv)
{
    $tsvContent = rtrim($tsvContent);
    file_put_contents(STORAGE_DIR . DIRECTORY_SEPARATOR . 'tsv' . DIRECTORY_SEPARATOR . 'record.tsv', $tsvContent);
}
