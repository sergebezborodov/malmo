<?php


return array(
    array(
        'id'           => 1,
        'title'        => 'first record',
        'user_id'      => 1,
        'date_updated' => new CDbExpression('NOW()'),
    ),
    array(
        'id'           => 3,
        'title'        => 'third record',
        'user_id'      => 30,
        'date_updated' => new CDbExpression('NOW()'),
    ),
    array(
        'id'           => 5,
        'title'        => 'fifth record',
        'user_id'      => 50,
        'date_updated' => new CDbExpression('NOW()'),
    ),
);
