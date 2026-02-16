<?php

namespace backoffice\tests\functional;

use backoffice\tests\FunctionalTester;
use common\fixtures\BackofficeUserFixture;

/**
 * Backoffice SiteController functional tests
 */
class SiteControllerCest
{
    /**
     * Load fixtures before db transaction begin
     *
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => BackofficeUserFixture::class,
                'dataFile' => codecept_data_dir() . 'backoffice_user.php',
            ],
        ];
    }

    public function _before(FunctionalTester $I)
    {
    }

    /**
     * Test that login page is accessible for guests
     */
    public function testLoginPageAccessibleForGuests(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->see('Login', 'h1');
        $I->see('Please fill out the following fields to login:');
        $I->seeElement('#login-form');
        $I->seeElement('input[name="LoginForm[username]"]');
        $I->seeElement('input[name="LoginForm[password]"]');
    }

    /**
     * Test login with valid credentials
     */
    public function testLoginWithValidCredentials(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->fillField('LoginForm[username]', 'erau');
        $I->fillField('LoginForm[password]', 'password_0');
        $I->click('login-button');

        $I->dontSee('Login');
        $I->see('Backoffice');
        $I->see('Logout');
    }

    /**
     * Test login with invalid password
     */
    public function testLoginWithInvalidPassword(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->fillField('LoginForm[username]', 'erau');
        $I->fillField('LoginForm[password]', 'wrong_password');
        $I->click('login-button');

        $I->see('Incorrect username or password.');
        $I->seeElement('#login-form');
    }

    /**
     * Test login with non-existent user
     */
    public function testLoginWithNonExistentUser(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->fillField('LoginForm[username]', 'nonexistent_user');
        $I->fillField('LoginForm[password]', 'some_password');
        $I->click('login-button');

        $I->see('Incorrect username or password.');
    }

    /**
     * Test login with empty credentials shows validation errors
     */
    public function testLoginWithEmptyCredentials(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->fillField('LoginForm[username]', '');
        $I->fillField('LoginForm[password]', '');
        $I->click('login-button');

        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    /**
     * Test that authenticated user is redirected from login page
     */
    public function testAuthenticatedUserRedirectedFromLoginPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(\common\models\BackofficeUser::findByUsername('erau'));
        $I->amOnRoute('site/login');
        $I->dontSee('Login');
        $I->see('Backoffice');
    }


    /**
     * Test that index page requires authentication
     */
    public function testIndexPageRequiresAuthentication(FunctionalTester $I)
    {
        $I->amOnRoute('site/index');
        // Should be redirected to login
        $I->seeInCurrentUrl('login');
    }

    /**
     * Test index page displays correct statistics for authenticated user
     */
    public function testIndexPageDisplaysStatistics(FunctionalTester $I)
    {
        $I->amLoggedInAs(\common\models\BackofficeUser::findByUsername('erau'));
        $I->amOnRoute('site/index');

        $I->see('Backoffice');
        $I->see('Frontpage Users');
        $I->see('Backoffice Users');
        $I->see('Key-Value Pairs');
    }

    /**
     * Test index page shows user roles
     */
    public function testIndexPageShowsUserRoles(FunctionalTester $I)
    {
        $I->amLoggedInAs(\common\models\BackofficeUser::findByUsername('erau'));
        $I->amOnRoute('site/index');

        $I->see('edit-backoffice-user');
    }

    /**
     * Test error page for guests
     */
    public function testErrorPageAccessible(FunctionalTester $I)
    {
        $I->amOnRoute('site/error');
        // Error page should be accessible
        $I->seeResponseCodeIsSuccessful();
    }
}
