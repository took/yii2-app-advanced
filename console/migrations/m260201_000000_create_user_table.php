<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m260201_000000_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // Create User table for users of the frontpage
        $this->createTable('{{%user}}', [
            'id_user' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'verification_token' => $this->string()->defaultValue(null),
            'email' => $this->string()->notNull(),
            'reputation' => $this->integer()->notNull()->defaultValue(100),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // Create unique indexes for fields that must be unique
        $this->createIndex('idx-user-auth_key', '{{%user}}', 'auth_key', true);
        $this->createIndex('idx-user-username', '{{%user}}', 'username', true);
        $this->createIndex('idx-user-email', '{{%user}}', 'email', true);
        $this->createIndex('idx-user-password_reset_token', '{{%user}}', 'password_reset_token', true);
        $this->createIndex('idx-user-verification_token', '{{%user}}', 'verification_token', true);

        // Create regular indexes for fields commonly used in queries
        $this->createIndex('idx-user-status', '{{%user}}', 'status');
        $this->createIndex('idx-user-reputation', '{{%user}}', 'reputation');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        // Drop indexes in reverse order
        $this->dropIndex('idx-user-reputation', '{{%user}}');
        $this->dropIndex('idx-user-status', '{{%user}}');
        $this->dropIndex('idx-user-verification_token', '{{%user}}');
        $this->dropIndex('idx-user-password_reset_token', '{{%user}}');
        $this->dropIndex('idx-user-email', '{{%user}}');
        $this->dropIndex('idx-user-username', '{{%user}}');
        $this->dropIndex('idx-user-auth_key', '{{%user}}');

        // Drop table
        $this->dropTable('{{%user}}');
    }
}
