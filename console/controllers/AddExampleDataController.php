<?php

namespace controllers;

class AddExampleDataController
{
    public function actionIndex(): int
    {
        echo 'Add example stage data...' . PHP_EOL;
        // Add example data for dev and stage to your models
        // TODO ...

        return \yii\console\ExitCode::OK;
    }
}
