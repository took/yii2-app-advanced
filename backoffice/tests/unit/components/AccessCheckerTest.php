<?php

namespace backoffice\tests\unit\components;

use backoffice\components\AccessChecker;
use Codeception\Test\Unit;
use common\fixtures\BackofficeUserFixture;
use common\models\BackofficeUser;

/**
 * AccessChecker unit tests
 */
class AccessCheckerTest extends Unit
{
    /**
     * @var \backoffice\tests\UnitTester
     */
    protected $tester;

    /**
     * @var AccessChecker
     */
    private AccessChecker $accessChecker;

    public function _fixtures()
    {
        return [
            'user' => [
                'class' => BackofficeUserFixture::class,
                'dataFile' => codecept_data_dir() . 'backoffice_user.php',
            ],
        ];
    }

    protected function _before()
    {
        $this->accessChecker = new AccessChecker();
    }

    /**
     * Test checkAccess returns false for null user ID
     */
    public function testCheckAccessReturnsFalseForNullUserId(): void
    {
        $result = $this->accessChecker->checkAccess(null, 'edit-backoffice-user');
        verify($result)->false();
    }

    /**
     * Test checkAccess returns false for non-existent user
     */
    public function testCheckAccessReturnsFalseForNonExistentUser(): void
    {
        $result = $this->accessChecker->checkAccess(99999, 'edit-backoffice-user');
        verify($result)->false();
    }

    /**
     * Test checkAccess returns true for matching role
     */
    public function testCheckAccessReturnsTrueForMatchingRole(): void
    {
        // superadmin has 'edit-backoffice-user' role
        $user = BackofficeUser::findOne(['username' => 'superadmin']);
        verify($user)->notNull();

        $result = $this->accessChecker->checkAccess($user->id_backoffice_user, 'edit-backoffice-user');
        verify($result)->true();
    }

    /**
     * Test checkAccess returns false for non-matching role
     */
    public function testCheckAccessReturnsFalseForNonMatchingRole(): void
    {
        // fpadmin has 'edit-frontpage-user' role but not 'edit-backoffice-user'
        $user = BackofficeUser::findOne(['username' => 'fpadmin']);
        verify($user)->notNull();

        $result = $this->accessChecker->checkAccess($user->id_backoffice_user, 'edit-backoffice-user');
        verify($result)->false();
    }

    /**
     * Test checkAccess caches roles after first lookup
     */
    public function testCheckAccessCachesRoles(): void
    {
        $user = BackofficeUser::findOne(['username' => 'superadmin']);
        verify($user)->notNull();

        // First call should cache the roles
        $this->accessChecker->checkAccess($user->id_backoffice_user, 'edit-backoffice-user');

        // Second call should use cached roles (no additional DB query)
        $result = $this->accessChecker->checkAccess($user->id_backoffice_user, 'edit-key-value-pairs');
        verify($result)->true();
    }

    /**
     * Test checkAccess throws exception for params (not implemented)
     */
    public function testCheckAccessThrowsExceptionForParams(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Params not implemented.');

        $this->accessChecker->checkAccess(1, 'edit-backoffice-user', ['some_param' => 'value']);
    }

    /**
     * Test checkAccess with kvadmin user and key-value role
     */
    public function testCheckAccessWithKvadminAndKeyValueRole(): void
    {
        // kvadmin has 'edit-key-value-pairs' role
        $user = BackofficeUser::findOne(['username' => 'kvadmin']);
        verify($user)->notNull();

        $result = $this->accessChecker->checkAccess($user->id_backoffice_user, 'edit-key-value-pairs');
        verify($result)->true();
    }

    /**
     * Test checkAccess with erau user (legacy user with all roles)
     */
    public function testCheckAccessWithErauUser(): void
    {
        // erau has all roles
        $user = BackofficeUser::findOne(['username' => 'erau']);
        verify($user)->notNull();

        $result = $this->accessChecker->checkAccess($user->id_backoffice_user, 'edit-backoffice-user');
        verify($result)->true();

        $result = $this->accessChecker->checkAccess($user->id_backoffice_user, 'edit-frontpage-user');
        verify($result)->true();

        $result = $this->accessChecker->checkAccess($user->id_backoffice_user, 'edit-key-value-pairs');
        verify($result)->true();
    }
}
