<?php

defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', false);
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);
defined('YII_DEBUG') or define('YII_DEBUG', true);

$_SERVER['SCRIPT_NAME']     = '/' . basename(__FILE__);
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require_once(dirname(__FILE__) . '/../malmo.php');
require_once(dirname(__FILE__) . '/TestApplication.php');
require_once('PHPUnit/Framework/TestCase.php');

Yii::import('system.test.*');

$config = require 'config.php';
$local  = require 'config-local.php';

$config = CMap::mergeArray($config, $local);

new TestApplication($config);
