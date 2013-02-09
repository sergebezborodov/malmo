<?php

Yii::app()->db->createCommand('DROP TABLE IF EXISTS item')->execute();

Yii::app()->db->createCommand()->createTable('item', array(
    'id'           => 'pk',
    'title'        => 'string',
    'user_id'      => 'int',
    'date_updated' => 'timestamp',
));
