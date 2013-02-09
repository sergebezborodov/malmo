<?php

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('MALMO_PATH') or define('MALMO_PATH', realpath(__DIR__.DS.'malmo'));
defined('MALMO_EXT_PATH') or define('MALMO_EXT_PATH', realpath(__DIR__.DS.'extensions'));

// application root
defined('ROOT') or define('ROOT', realpath(__DIR__.DS.'..'));


defined('YII_PATH') or define('YII_PATH', (__DIR__.DS.'yii'));

// default subdomain for backend part of application
defined('BACKEND_SUBDOMAIN') or define('BACKEND_SUBDOMAIN', 'admin');

require_once YII_PATH . DS . 'YiiBase.php';
require_once MALMO_PATH . DS . 'Malmo.php';

Malmo::init();

class Yii extends Malmo {}
