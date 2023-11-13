<?php

declare(strict_types = 1);

if (!defined('APPLICATION_STORE')) {
    define('APPLICATION_STORE', 'DE');
}

$pathToAutoloader = codecept_root_dir('vendor/autoload.php');

if (!file_exists($pathToAutoloader)) {
    $pathToAutoloader = codecept_root_dir('../../autoload.php');
}

require_once $pathToAutoloader;
