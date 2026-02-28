<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%key_value}}`.
 */
class m260203_000000_create_key_value_table extends Migration
{
    public function safeUp(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%key_value}}', [
            'id_key_value' => $this->primaryKey(),
            'key' => $this->string(250)->notNull(),
            'value' => $this->string(2500)->notNull(),
            'created_by' => $this->integer()->defaultValue(null),
            'updated_by' => $this->integer()->defaultValue(null),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // Create unique index for key column
        $this->createIndex('idx-key-value-key', '{{%key_value}}', 'key', true);

        // Create indexes for timestamp columns
        $this->createIndex('idx-key-value-created_at', '{{%key_value}}', 'created_at');
        $this->createIndex('idx-key-value-updated_at', '{{%key_value}}', 'updated_at');

        // Create indexes and foreign keys for blameable columns
        $this->createIndex('idx-key-value-created_by', '{{%key_value}}', 'created_by');
        $this->createIndex('idx-key-value-updated_by', '{{%key_value}}', 'updated_by');

        $this->addForeignKey(
            'fk-key-value-created_by',
            '{{%key_value}}',
            'created_by',
            '{{%backoffice_user}}',
            'id_backoffice_user',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-key-value-updated_by',
            '{{%key_value}}',
            'updated_by',
            '{{%backoffice_user}}',
            'id_backoffice_user',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        // Drop foreign keys first
        $this->dropForeignKey('fk-key-value-updated_by', '{{%key_value}}');
        $this->dropForeignKey('fk-key-value-created_by', '{{%key_value}}');

        // Drop indexes
        $this->dropIndex('idx-key-value-updated_by', '{{%key_value}}');
        $this->dropIndex('idx-key-value-created_by', '{{%key_value}}');
        $this->dropIndex('idx-key-value-updated_at', '{{%key_value}}');
        $this->dropIndex('idx-key-value-created_at', '{{%key_value}}');
        $this->dropIndex('idx-key-value-key', '{{%key_value}}');

        // Drop table
        $this->dropTable('{{%key_value}}');
    }
}
