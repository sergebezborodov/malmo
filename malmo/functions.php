<?php
/**
 * Global functions
 */

/**
 * Simple print_r dump
 *
 * @param mixed $var
 * @param bool $end
 */
function dd($var, $end = true)
{
    if (defined('CONSOLE_APP')) {
        print_r($var);
    } else {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }

    if ($end) {
        exit;
    }
}

/**
 * Dump active record attributes
 * If active record instance given - dumps it attributes
 * If array of AR given - dump its attrbutes in foreach cycle
 *
 * @param mixed $var
 * @param bool $end
 */
function dar($var, $end = true)
{
    if (is_array($var)) {
        foreach ($var as $i) {
            print_r($i->attributes);
        }
    } else {
        print_r($var->attributes);
    }

    if ($end) {
        Yii::app()->end();
    }
}

/**
 * Colorized dump by CVarDumper
 *
 * @param mixed $var
 * @param bool $end
 */
function ddd($var, $end = true)
{
    echo '<pre>';
    CVarDumper::dump($var, 10, true);
    echo '</pre>';

    if ($end) {
        exit;
    }
}


