<?php

namespace console\controllers;

use common\models\BackofficeUser;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;

/**
 * CreateDefaultBackofficeUserController creates default admin users for development/testing.
 *
 * This controller handles the creation of default admin users during application
 * initialization, particularly for Docker container setup and development environments.
 *
 * Usage:
 * ```
 * php yii create-default-backoffice-user
 * php yii create-default-backoffice-user --username=myuser --password=mypass --email=user@example.com
 * ```
 */
class CreateDefaultBackofficeUserController extends Controller
{
    /**
     * @var string Username for the admin user (default: 'admin')
     */
    public string $username = 'admin';

    /**
     * @var string Password for the admin user (default: 'admin123')
     */
    public string $password = 'admin123';

    /**
     * @var string Email for the admin user (default: 'admin@example.com')
     */
    public string $email = 'admin@example.com';

    /**
     * @var string Roles for the admin user (default: 'edit-backoffice-user')
     */
    public string $roles = 'edit-backoffice-user';

    /**
     * {@inheritdoc}
     */
    public function options($actionID): array
    {
        return array_merge(parent::options($actionID), [
            'username',
            'password',
            'email',
            'roles'
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
            'r' => 'roles'
        ]);
    }

    /**
     * Creates a default admin user for testing/development (default action).
     *
     * This action creates an admin user with the specified credentials.
     * It checks if a user with the same username or email already exists
     * before creating a new one, making it safe to run multiple times.
     *
     * @return int Exit code (ExitCode::OK on success, ExitCode::UNSPECIFIED_ERROR on failure)
     * @throws Exception|\yii\base\Exception
     */
    public function actionIndex(): int
    {
        $this->stdout("==> Creating admin user...\n");

        // Check if user already exists
        $existingUser = BackofficeUser::findOne(['username' => $this->username]);
        if ($existingUser) {
            $this->stdout("    ⚠️  User '{$this->username}' already exists, skipping...\n");
            return ExitCode::OK;
        }

        $existingEmail = BackofficeUser::findOne(['email' => $this->email]);
        if ($existingEmail) {
            $this->stdout("    ⚠️  Email '{$this->email}' already in use, skipping...\n");
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
            $this->stdout("    ✓ Default admin user created successfully!\n");
            $this->stdout("      Username: {$this->username}\n");
            $this->stdout("      Password: {$this->password}\n");
            $this->stdout("      Email: {$this->email}\n");
            $this->stdout("      ⚠️  IMPORTANT: Change this password after first login!\n");
            return ExitCode::OK;
        } else {
            $this->stderr("    ✗ Error creating admin user:\n");
            foreach ($user->errors as $attribute => $errors) {
                foreach ($errors as $error) {
                    $this->stderr("      - {$attribute}: {$error}\n");
                }
            }
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
