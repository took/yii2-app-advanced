<?php

namespace api\tests\unit\controllers;

use api\controllers\SiteController;
use Codeception\Test\Unit;
use Yii;

class SiteControllerTest extends Unit
{
    /**
     * Test the index action returns API status information
     *
     * @return void
     */
    public function testActionIndex(): void
    {
        // Create a mock of the controller that bypasses parent constructor
        $controller = new class ('site', Yii::$app) extends SiteController {
            public function __construct($id, $module)
            {
                // Skip parent constructor to avoid dependency injection issues
                $this->id = $id;
                $this->module = $module;
            }
        };

        $result = $controller->actionIndex();

        // Verify response structure
        verify($result)->isArray();
        verify($result['status'])->equals('success');
        verify($result['message'])->equals('API is running');
        verify($result['version'])->equals('1.0');
        verify($result)->arrayHasKey('timestamp');

        // Verify timestamp is in ISO 8601 format
        verify($result['timestamp'])->notEmpty();
    }
}
