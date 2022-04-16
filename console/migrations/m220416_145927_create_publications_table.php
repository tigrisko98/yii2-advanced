<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%publications}}`.
 */
class m220416_145927_create_publications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%publications}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'caption' => $this->string(),
            'images_urls' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%publications}}');
    }
}
