<?php

use yii\db\Migration;

class m170531_085621_add_users_role_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'role', $this->string()->notNull()->defaultValue('user'));

        $this->update('{{%users}}', ['role' => 'user']);
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'role');
    }
}