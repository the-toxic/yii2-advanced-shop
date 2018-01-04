<?php

use yii\db\Migration;

class m170605_082707_add_user_phone_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'phone', $this->string()->defaultValue(NULL));
        $this->addColumn('{{%users}}', 'phone_confirmed', $this->integer()->notNull()->defaultValue(0));

        $this->createIndex('{{%idx-users-phone}}', '{{%users}}', 'phone', true);
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'phone');
        $this->dropColumn('{{%users}}', 'phone_confirmed');
    }
}