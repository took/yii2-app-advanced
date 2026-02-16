<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=mysql-yii2advanced-stage;dbname=yii2advanced_stage',
            'username' => 'yii2advanced-stage',
            'password' => 'secret',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                // User host maildev-yii2advanced-stage in docker environment
                'dsn' => 'smtp://maildev-yii2advanced-stage:1025'
            ]
        ],
    ],
];
