<?php

require 'functions.php';

/**
 * Malmo main class
 */
class Malmo extends YiiBase
{
    private static $_classMap;


    /**
     * Creates a web application
     *
     * @static
     * @param array $config
     * @return MWebApplication
     */
    public static function createWebApplication($config = null)
    {
        return self::createApplication('MWebApplication', $config);
    }

    /**
     * Creates a console application
     *
     * @static
     * @param array $config
     * @return MConsoleApplication
     */
    public static function createConsoleApplication($config = null)
    {
        return parent::createApplication('MConsoleApplication', $config);
    }

    /**
     * Creates a gearman console application
     *
     * @static
     * @param array $config
     * @return MWorkerApplication
     */
    public static function createWorkerApplication($config = null)
    {
        return parent::createApplication('MWorkerApplication', $config);
    }


    /**
     * Init Malmo
     */
    public static function init()
    {
        self::setPathOfAlias('root', ROOT);
        self::setPathOfAlias('malmo', MALMO_PATH);
        self::setPathOfAlias('malmo-ext', MALMO_EXT_PATH);

        self::$_classMap = require 'classmap.php';
        self::registerAutoloader(array('Malmo', 'autoload'));
    }

    /**
     * Autoload for malmo internal classes
     *
     * @static
     * @param string $className
     * @return bool
     */
    public static function autoload($className)
    {
        if (isset(self::$_classMap[$className])) {
            include(MALMO_PATH . self::$_classMap[$className]);
            return true;
        }
        return false;
    }

    /**
     * @static
     * @param $alias
     * @param bool $forceInclude
     * @return string
     */
    public static function import($alias, $forceInclude = false)
    {
        if (isset(self::$_classMap[$alias])) {
            include_once MALMO_PATH . self::$_classMap[$alias];
        }

        return parent::import($alias, $forceInclude);
    }
}
