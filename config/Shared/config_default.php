<?php

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\PropelOrm\PropelOrmConstants;
use Spryker\Zed\Propel\PropelConfig;

$config[KernelConstants::PROJECT_NAMESPACES] = [
    'ValanticSpryker',
];

$config[KernelConstants::CORE_NAMESPACES] = [
    'SprykerShop',
    'SprykerEco',
    'Spryker',
    'SprykerSdk',
    'ValanticSpryker',
];

$config[PropelConstants::ZED_DB_ENGINE] = PropelConfig::DB_ENGINE_MYSQL;

$config[PropelConstants::USE_SUDO_TO_MANAGE_DATABASE] = false;

$config[PropelOrmConstants::PROPEL_SHOW_EXTENDED_EXCEPTION] = true;
$config[PropelConstants::ZED_DB_USERNAME] = 'spryker';
$config[PropelConstants::ZED_DB_PASSWORD] = 'secret';
$config[PropelConstants::ZED_DB_HOST] = 'database';
$config[PropelConstants::ZED_DB_PORT] = 3306;
$config[PropelConstants::ZED_DB_ENGINE] = PropelConfig::DB_ENGINE_MYSQL;
$config[PropelConstants::ZED_DB_DATABASE] = 'eu-docker';

include 'config_propel.php';

