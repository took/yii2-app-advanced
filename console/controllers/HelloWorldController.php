<?php

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;

class HelloWorldController extends Controller
{
    public function actionIndex(): int
    {
        echo 'Hello World!' . PHP_EOL;
        return ExitCode::OK;
    }
}
