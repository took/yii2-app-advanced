# RBAC (Role-Based Access Control)

This document describes the RBAC implementation for the backoffice application.

## Overview

The application uses a simple RBAC system where roles are stored as comma-separated strings in the `BackofficeUser.roles` field. The `AccessChecker` component validates access based on these roles.

## Available Roles

The following roles are defined in `common/config/params.php`:

| Role | Description | Implied Permissions |
|------|-------------|---------------------|
| `view-key-value-pairs` | View key-value configuration pairs | - |
| `edit-key-value-pairs` | Create, update, delete key-value pairs | Includes `view-key-value-pairs` |
| `invite-frontpage-user` | Invite new frontpage users, resend invite links | - |
| `edit-frontpage-user` | Full management of frontpage users | Includes `invite-frontpage-user` |
| `edit-backoffice-user` | Full management of backoffice users | Highest level access |

## Role Hierarchy

Roles have an implicit hierarchy:

```
edit-backoffice-user (highest)
├── edit-frontpage-user
│   └── invite-frontpage-user
└── edit-key-value-pairs
    └── view-key-value-pairs (lowest)
```

## Usage

### Checking Access in Controllers

```php
public function behaviors(): array
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['index', 'view'],
                    'allow' => true,
                    'roles' => ['view-key-value-pairs', 'edit-key-value-pairs'],
                ],
                [
                    'actions' => ['create', 'update', 'delete'],
                    'allow' => true,
                    'roles' => ['edit-key-value-pairs'],
                ],
            ],
        ],
    ];
}
```

### Console Commands

Initialize RBAC with default admin user:

```bash
# Create default admin user with all roles
php yii rbac/init

# Create with custom credentials
php yii rbac/init --username=myadmin --password=secret123 --email=admin@myapp.com

# Show current RBAC configuration
php yii rbac/show-config

# List all backoffice users and their roles
php yii rbac/users
```

### AccessChecker Component

The `AccessChecker` is configured in `backoffice/config/main.php`:

```php
'user' => [
    'accessChecker' => 'backoffice\components\AccessChecker',
],
```

It implements `CheckAccessInterface` and reads roles from the user's `roles` field.

## Test Fixtures

Test users with specific roles are provided in the fixtures:

| Username | Password | Roles |
|----------|----------|-------|
| `superadmin` | password_0 | All roles |
| `kvadmin` | Test1234 | edit-key-value-pairs, view-key-value-pairs |
| `kvviewer` | Test1234 | view-key-value-pairs |
| `fpadmin` | Test1234 | edit-frontpage-user, invite-frontpage-user |
| `fpinviter` | Test1234 | invite-frontpage-user |
| `boadmin` | Test1234 | edit-backoffice-user |
| `admin` | password_0 | All roles (legacy) |

## Security Notes

1. **Role Assignment**: Only users with `edit-backoffice-user` role can assign roles to other users.
2. **Self-Protection**: Users cannot remove their own `edit-backoffice-user` role or delete themselves.
3. **Default Passwords**: Always change default passwords after creating admin users.
