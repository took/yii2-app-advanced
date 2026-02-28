<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (YII_ENV == 'dev') {
    // configuration adjustments for 'dev' environment
    $allowedIps = explode(',', getenv('YII_DEBUG_ALLOWED_IPS') ?: '127.0.0.1');

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        'allowedIPs' => $allowedIps,
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        'allowedIPs' => $allowedIps,
    ];
}

return $config;
