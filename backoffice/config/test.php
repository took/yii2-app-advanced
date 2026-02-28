<?php

return [
    'id' => 'app-backoffice-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => 'common\models\BackofficeUser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backoffice', 'httpOnly' => true],
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
    ],
];
