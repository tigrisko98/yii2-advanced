<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formValidator' => [
            'class' => 'common\components\FormValidator',
        ],
        's3' => [
            'class' => 'bpsys\yii2\aws\s3\Service',
            'credentials' => [ // Aws\Credentials\CredentialsInterface|array|callable
                'key' => 'AKIA5TO7PAOT5Y6FNZBJ',
                'secret' => '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',
            ],
            'region' => 'eu-central-1',
            'defaultBucket' => 'kokoden-images',
            'defaultAcl' => 'public-read',
            //'defaultPresignedExpiration' => '+1 hour',
        ],
    ],
];
