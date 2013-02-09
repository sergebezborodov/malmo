<?php

return array(
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=malmo_test',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
        'fixture'=>array(
            'class'=>'system.test.CDbFixtureManager',
            'basePath' => __DIR__.DS.'malmo'.DS.'fixtures',
        ),
    ),
);
