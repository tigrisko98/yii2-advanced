<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%comment}}`.
 */
class m220419_190738_add_user_nickname_and_user_avatar_url_columns_to_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%comments}}', 'user_nickname', $this->string()->notNull());
        $this->addColumn('{{%comments}}', 'user_avatar_url', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%comments}}', 'user_nickname');
        $this->dropColumn('{{%comments}}', 'user_avatar_url');
    }
}
