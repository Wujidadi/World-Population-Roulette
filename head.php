<?php

define('BASE_DIR', __DIR__);

define('VENDOR_DIR',   BASE_DIR . DIRECTORY_SEPARATOR . 'vendor');
define('LIBRARY_DIR',  BASE_DIR . DIRECTORY_SEPARATOR . 'libraries');
define('CLI_DIR',      BASE_DIR . DIRECTORY_SEPARATOR . 'cli');
define('DATABASE_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'database');
define('STORAGE_DIR',  BASE_DIR . DIRECTORY_SEPARATOR . 'storage');

define('MIGRATION_DIR', DATABASE_DIR . DIRECTORY_SEPARATOR . 'migrations');
define('LOG_DIR',       STORAGE_DIR  . DIRECTORY_SEPARATOR . 'logs');

define('CLI_COLOR_SUCCESS', '#FFEF9A');
define('CLI_COLOR_ERROR',   '#FFBBBB');

define('CLI_COLOR_SERIAL',   '#FFFF00');
define('CLI_COLOR_LOCATION', '#00FFFF');

require_once VENDOR_DIR  . DIRECTORY_SEPARATOR . 'autoload.php';
require_once LIBRARY_DIR . DIRECTORY_SEPARATOR . 'Framework' . DIRECTORY_SEPARATOR . 'helpers.php';
