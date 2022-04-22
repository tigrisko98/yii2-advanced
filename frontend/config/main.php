<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'STOgram',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' =>
                [
                    '/user/edit-settings' => '/user/edit-settings',
                    '/user/<nickname>/followers' => '/user/followers',
                    '/user/<nickname>/following' => '/user/following',
                    '/user/follow' => '/user/follow',
                    '/user/unfollow' => '/user/unfollow',
                    '/user/<nickname>' => '/user/view',

                    '/publication/create' => '/publication/create',
                    '/publication/<id>' => '/publication/view',
                    '/publication/<id>/delete' => '/publication/delete',
                    '/publication/<id>/edit' => '/publication/edit',
                    '/publication/<id>/comment' => '/comment/create',

                    '/comment/<id>/edit' => '/comment/edit',
                    '/comment/<id>/delete' => '/comment/delete'
                ],
        ],
    ],
    'params' => $params,
];
