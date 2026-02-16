<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontpage',
    'name' => 'My Application',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontpage\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'container' => [
        'definitions' => [
            \yii\widgets\ListView::class => [
                'pager' => [
                    'class' => \yii\bootstrap5\LinkPager::class,
                ],
            ],
            \yii\grid\GridView::class => [
                'pager' => [
                    'class' => \yii\bootstrap5\LinkPager::class,
                ],
            ],
            \kartik\grid\GridView::class => [
                'pager' => [
                    'class' => \yii\bootstrap5\LinkPager::class,
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontpage',
        ],
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontpage', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontpage
            'name' => 'advanced-frontpage',
        ],
        'log' => [
            'traceLevel' => (defined('YII_DEBUG') && YII_DEBUG) ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
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
            'rules' => [
                'fnords' => 'fnord/index',
                'fnord/<id_fnord:\d+>' => 'fnord/view',
            ],
        ],
    ],
    'params' => $params,
];
