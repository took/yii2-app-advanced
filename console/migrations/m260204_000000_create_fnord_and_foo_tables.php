<?php

use yii\db\Migration;

/**
 * Handles the creation of tables `{{%fnord}}` and `{{%foo}}`.
 */
class m260204_000000_create_fnord_and_foo_tables extends Migration
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

        // Create fnord table
        $this->createTable('{{%fnord}}', ['id_fnord' => $this->primaryKey(), 'bar' => $this->string(255), 'baz' => $this->string(255),], $tableOptions);

        // Create foo table (every fnord may have one or more foo)
        $this->createTable('{{%foo}}', ['id_foo' => $this->primaryKey(), 'id_fnord' => $this->integer()->notNull(), // Every foo belongs to a fnord!
            'foo_value' => $this->integer()->defaultValue(0),], $tableOptions);

        // Create index for foreign key
        $this->createIndex('idx-foo-id_fnord', '{{%foo}}', 'id_fnord');

        // Add foreign key constraint
        $this->addForeignKey('fk-foo-fnord', '{{%foo}}', 'id_fnord', '{{%fnord}}', 'id_fnord', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        // Drop foreign key first
        $this->dropForeignKey('fk-foo-fnord', '{{%foo}}');

        // Drop index
        $this->dropIndex('idx-foo-id_fnord', '{{%foo}}');

        // Drop tables
        $this->dropTable('{{%foo}}');
        $this->dropTable('{{%fnord}}');
    }
}
