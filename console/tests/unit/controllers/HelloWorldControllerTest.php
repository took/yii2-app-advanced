<?php

namespace console\tests\unit\controllers;

use Codeception\Test\Unit;
use console\controllers\HelloWorldController;
use Yii;
use yii\console\ExitCode;

class HelloWorldControllerTest extends Unit
{
    /**
     * Test that actionIndex outputs "Hello World!" and returns success exit code
     *
     * @return void
     */
    public function testActionIndex(): void
    {
        // Create a mock of the controller that bypasses parent constructor
        $controller = new class ('hello-world', Yii::$app) extends HelloWorldController {
            public function __construct($id, $module)
            {
                // Skip parent constructor to avoid dependency injection issues
                $this->id = $id;
                $this->module = $module;
            }
        };

        // Capture output
        ob_start();
        $exitCode = $controller->actionIndex();
        $output = ob_get_clean();

        // Verify exit code
        verify($exitCode)->equals(ExitCode::OK);

        // Verify output
        verify($output)->stringContainsString('Hello World!');
    }
}
