<?php

namespace controllers;

class HelloWorldController
{
    public function actionIndex(): int
    {
        echo 'Hello World!' . PHP_EOL;
        return \yii\console\ExitCode::OK;
    }
}
