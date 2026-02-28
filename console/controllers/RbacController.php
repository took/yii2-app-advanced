<?php

namespace console\controllers;

use common\models\BackofficeUser;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * RBAC (Role-Based Access Control) management controller.
 *
 * This controller provides commands for managing RBAC roles and permissions
 * in the backoffice application.
 *
 * Usage:
 * ```
 * # Initialize RBAC with default admin user
 * php yii rbac/init
 *
 * # Show current RBAC configuration
 * php yii rbac/show-config
 * ```
 */
class RbacController extends Controller
{
    /**
     * @var string Default admin username
     */
    public string $username = 'admin';

    /**
     * @var string Default admin password
     */
    public string $password = 'admin123';

    /**
     * @var string Default admin email
     */
    public string $email = 'admin@example.com';

    /**
     * @var string Comma-separated list of roles for the admin user
     */
    public string $roles = 'edit-backoffice-user,edit-frontpage-user,edit-key-value-pairs,view-key-value-pairs';

    /**
     * {@inheritdoc}
     */
    public function options($actionID): array
    {
        return array_merge(parent::options($actionID), [
            'username',
            'password',
            'email',
            'roles',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function optionAliases(): array
    {
        return array_merge(parent::optionAliases(), [
            'u' => 'username',
            'p' => 'password',
            'e' => 'email',
            'r' => 'roles',
        ]);
    }

    /**
     * Initialize RBAC with a default admin user (default action).
     *
     * Creates a default admin user with all roles assigned if no user exists.
     * Safe to run multiple times - will skip if user already exists.
     *
     * @return int Exit code
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionIndex(): int
    {
        return $this->actionInit();
    }

    /**
     * Initialize RBAC with a default admin user.
     *
     * Creates a default admin user with all roles assigned if no user exists.
     *
     * @return int Exit code
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionInit(): int
    {
        $this->stdout("==> Initializing RBAC...\n", Console::FG_CYAN);
        $this->stdout("    Creating default admin user...\n");

        // Check if user already exists
        $existingUser = BackofficeUser::findOne(['username' => $this->username]);
        if ($existingUser) {
            $this->stdout("    ⚠️  User '{$this->username}' already exists.\n", Console::FG_YELLOW);
            $this->stdout("    Use 'php yii rbac/roles' to view existing user roles.\n");
            return ExitCode::OK;
        }

        $existingEmail = BackofficeUser::findOne(['email' => $this->email]);
        if ($existingEmail) {
            $this->stdout("    ⚠️  Email '{$this->email}' already in use.\n", Console::FG_YELLOW);
            return ExitCode::OK;
        }

        // Create new admin user
        $user = new BackofficeUser();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->roles = $this->roles;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = BackofficeUser::STATUS_ACTIVE;

        if ($user->save()) {
            $this->stdout("    ✓ Default admin user created successfully!\n", Console::FG_GREEN);
            $this->stdout("      Username: {$this->username}\n", Console::FG_GREY);
            $this->stdout("      Password: {$this->password}\n", Console::FG_GREY);
            $this->stdout("      Email: {$this->email}\n", Console::FG_GREY);
            $this->stdout("      Roles: {$this->roles}\n", Console::FG_GREY);
            $this->stdout("\n", Console::FG_GREY);
            $this->stdout("    ⚠️  IMPORTANT: Change this password after first login!\n", Console::FG_RED);
            return ExitCode::OK;
        } else {
            $this->stderr("    ✗ Error creating admin user:\n", Console::FG_RED);
            foreach ($user->errors as $attribute => $errors) {
                foreach ($errors as $error) {
                    $this->stderr("      - {$attribute}: {$error}\n", Console::FG_RED);
                }
            }
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    /**
     * Display current RBAC configuration.
     *
     * Shows all configured roles from params and existing backoffice users.
     *
     * @return int Exit code
     */
    public function actionShowConfig(): int
    {
        $this->stdout("==> RBAC Configuration\n", Console::FG_CYAN);
        $this->stdout("======================\n\n");

        // Show defined roles
        $this->stdout("Available Roles:\n", Console::FG_YELLOW);
        $roles = \Yii::$app->params['backofficeRoles'] ?? [];
        foreach ($roles as $role => $description) {
            $this->stdout("  - {$role}: {$description}\n");
        }

        if (empty($roles)) {
            $this->stdout("  (No roles defined in params)\n", Console::FG_GREY);
        }

        $this->stdout("\n");

        // Show existing backoffice users
        $this->stdout("Existing Backoffice Users:\n", Console::FG_YELLOW);
        $users = BackofficeUser::find()->all();
        if (empty($users)) {
            $this->stdout("  (No users found)\n", Console::FG_GREY);
        } else {
            foreach ($users as $user) {
                $statusLabel = $user->status === BackofficeUser::STATUS_ACTIVE ? 'Active' : 'Inactive';
                $this->stdout("  - {$user->username} ({$user->email}) [{$statusLabel}]\n");
                $this->stdout("    Roles: " . ($user->roles ?: '(none)') . "\n", Console::FG_GREY);
            }
        }

        $this->stdout("\n");

        // Show usage hint
        $this->stdout("Usage:\n", Console::FG_GREEN);
        $this->stdout("  Create admin user: php yii rbac/init\n");
        $this->stdout("  With custom options: php yii rbac/init --username=myadmin --password=secret123\n");

        return ExitCode::OK;
    }

    /**
     * List all backoffice users and their roles.
     *
     * @return int Exit code
     */
    public function actionUsers(): int
    {
        $this->stdout("==> Backoffice Users\n", Console::FG_CYAN);
        $this->stdout("====================\n\n");

        $users = BackofficeUser::find()->all();
        if (empty($users)) {
            $this->stdout("No users found.\n", Console::FG_GREY);
            $this->stdout("Run 'php yii rbac/init' to create an admin user.\n");
            return ExitCode::OK;
        }

        foreach ($users as $user) {
            $statusColor = $user->status === BackofficeUser::STATUS_ACTIVE ? Console::FG_GREEN : Console::FG_GREY;
            $statusLabel = $user->status === BackofficeUser::STATUS_ACTIVE ? 'Active' : 'Inactive';

            $this->stdout("User: {$user->username}\n", Console::FG_YELLOW);
            $this->stdout("  Email: {$user->email}\n");
            $this->stdout("  Status: ", Console::FG_GREY);
            $this->stdout("{$statusLabel}\n", $statusColor);
            $this->stdout("  Roles: " . ($user->roles ?: '(none)') . "\n", Console::FG_GREY);
            $this->stdout("\n");
        }

        return ExitCode::OK;
    }
}
