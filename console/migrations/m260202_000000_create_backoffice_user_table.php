<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%backoffice_user}}`.
 */
class m260202_000000_create_backoffice_user_table extends Migration
{
    public function safeUp(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%backoffice_user}}', [
            'id_backoffice_user' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token' => $this->string()->defaultValue(null),
            'email' => $this->string()->notNull()->unique(),
            // Using a comma separated list for roles assigned to user, intentionally breaking NF1 here to KISS
            'roles' => $this->string(2500)->notNull()->defaultValue(''),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // Create unique indexes for fields that must be unique
        $this->createIndex('idx-backoffice-user-auth_key', '{{%backoffice_user}}', 'auth_key', true);
        $this->createIndex('idx-backoffice-user-username', '{{%backoffice_user}}', 'username', true);
        $this->createIndex('idx-backoffice-user-email', '{{%backoffice_user}}', 'email', true);
        $this->createIndex('idx-backoffice-user-password_reset_token', '{{%backoffice_user}}', 'password_reset_token', true);
        $this->createIndex('idx-backoffice-user-verification_token', '{{%backoffice_user}}', 'verification_token', true);

        // Create regular indexes for fields commonly used in queries
        $this->createIndex('idx-backoffice-user-status', '{{%backoffice_user}}', 'status');
    }

    public function safeDown(): void
    {
        // Drop indexes in reverse order
        $this->dropIndex('idx-backoffice-user-status', '{{%backoffice_user}}');
        $this->dropIndex('idx-backoffice-user-verification_token', '{{%backoffice_user}}');
        $this->dropIndex('idx-backoffice-user-password_reset_token', '{{%backoffice_user}}');
        $this->dropIndex('idx-backoffice-user-email', '{{%backoffice_user}}');
        $this->dropIndex('idx-backoffice-user-username', '{{%backoffice_user}}');
        $this->dropIndex('idx-backoffice-user-auth_key', '{{%backoffice_user}}');

        // Drop table
        $this->dropTable('{{%backoffice_user}}');
    }
}
