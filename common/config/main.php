<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@usersAvatarsFolder' => 'images/users-avatars',
        '@usersPublicationsFolder' => 'images/users-publications'
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formValidator' => [
            'class' => 'common\components\FormValidator',
        ],
    ],
];
