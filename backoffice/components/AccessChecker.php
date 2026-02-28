<?php

namespace backoffice\components;

use common\models\BackofficeUser;
use Exception;
use yii\base\Component;
use yii\rbac\CheckAccessInterface;

/**
 * AccessChecker provides role-based access control for the backoffice application.
 *
 * This component implements a simple RBAC system where roles are stored as comma-separated
 * strings in the BackofficeUser roles field. It caches user roles in memory for performance.
 *
 * @property array $_roles Internal cache of user roles indexed by user ID
 */
class AccessChecker extends Component implements CheckAccessInterface
{
    /**
     * @var array Internal cache of user roles indexed by user ID
     */
    private array $_roles = [];

    /**
     * Checks if the user has access to the specified permission.
     *
     * This method retrieves the user's roles from the BackofficeUser model and checks
     * if the requested permission name exists in their role list. User roles are
     * cached in memory to avoid repeated database queries.
     *
     * @param string|int $userId The user ID to check access for
     * @param string $permissionName The permission name (role) to check
     * @param array $params Additional parameters (not currently implemented)
     * @return bool True if the user has the permission, false otherwise
     * @throws Exception If params are provided (not supported)
     */
    public function checkAccess($userId, $permissionName, $params = []): bool
    {
        if (count($params)) {
            throw new Exception('Params not implemented.');
        }

        if (!$userId) {
            return false;
        }

        if (!isset($this->_roles[$userId])) {
            $user = BackofficeUser::findIdentity($userId);
            $roles = [];
            if ($user) {
                $roles = explode(',', $user->roles);
            }
            $this->_roles[$userId] = $roles;
        }

        if (in_array($permissionName, $this->_roles[$userId])) {
            return true;
        }
        return false;
    }
}
