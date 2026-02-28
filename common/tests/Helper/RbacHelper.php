<?php

namespace common\tests\Helper;

use common\models\BackofficeUser;

/**
 * RBAC helper for tests.
 *
 * Provides utility methods for role-based access control testing.
 */
class RbacHelper
{
    /**
     * Get a user with the specified role.
     *
     * @param string $role The role to search for
     * @return BackofficeUser|null The user with the role, or null if not found
     */
    public static function getUserWithRole(string $role): ?BackofficeUser
    {
        $users = BackofficeUser::find()->all();
        foreach ($users as $user) {
            $roles = explode(',', $user->roles);
            if (in_array($role, $roles)) {
                return $user;
            }
        }
        return null;
    }

    /**
     * Get a super admin user (has all roles).
     *
     * @return BackofficeUser|null
     */
    public static function getSuperAdmin(): ?BackofficeUser
    {
        return self::getUserWithRole('edit-backoffice-user');
    }

    /**
     * Get a key-value admin user.
     *
     * @return BackofficeUser|null
     */
    public static function getKvAdmin(): ?BackofficeUser
    {
        return self::getUserWithRole('edit-key-value-pairs');
    }

    /**
     * Get a key-value viewer user.
     *
     * @return BackofficeUser|null
     */
    public static function getKvViewer(): ?BackofficeUser
    {
        return self::getUserWithRole('view-key-value-pairs');
    }

    /**
     * Get a frontpage admin user.
     *
     * @return BackofficeUser|null
     */
    public static function getFpAdmin(): ?BackofficeUser
    {
        return self::getUserWithRole('edit-frontpage-user');
    }

    /**
     * Get a frontpage inviter user.
     *
     * @return BackofficeUser|null
     */
    public static function getFpInviter(): ?BackofficeUser
    {
        return self::getUserWithRole('invite-frontpage-user');
    }

    /**
     * Check if a user has a specific role.
     *
     * @param BackofficeUser $user The user to check
     * @param string $role The role to check for
     * @return bool True if user has the role
     */
    public static function userHasRole(BackofficeUser $user, string $role): bool
    {
        $roles = explode(',', $user->roles);
        return in_array($role, $roles);
    }

    /**
     * Get all available roles from params.
     *
     * @return array Array of role => description
     */
    public static function getAvailableRoles(): array
    {
        return \Yii::$app->params['backofficeRoles'] ?? [];
    }
}
