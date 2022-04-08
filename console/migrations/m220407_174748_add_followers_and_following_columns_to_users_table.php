<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m220407_174748_add_followers_and_following_columns_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%users}}', 'followers', $this->getDb()->getSchema()
            ->createColumnSchemaBuilder('longtext')->defaultValue(null));

        $this->addColumn('{{%users}}', 'following', $this->getDb()->getSchema()
            ->createColumnSchemaBuilder('longtext')->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%users}}', 'followers');
        $this->dropColumn('{{%users}}', 'following');
    }
}
