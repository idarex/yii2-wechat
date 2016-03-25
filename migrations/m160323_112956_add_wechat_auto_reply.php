<?php

use yii\db\Migration;

class m160323_112956_add_wechat_auto_reply extends Migration
{
    public function up()
    {
        $this->addColumn('wechat_auto_reply','reply_type',$this->string(32)->notNull()->defaultValue(''));
    }

    public function down()
    {
        $this->dropColumn('wechat_auto_reply','reply_type');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
