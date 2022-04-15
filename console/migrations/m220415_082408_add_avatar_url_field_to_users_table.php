<?php

use yii\db\Migration;

/**
 * Class m220415_082408_add_avatar_url_field_to_users_table
 */
class m220415_082408_add_avatar_url_field_to_users_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%users}}', 'avatar_url', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%users}}', 'avatar_url');
    }
}
