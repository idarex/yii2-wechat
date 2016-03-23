<?php

use yii\db\Migration;

class m160321_063800_alter_table_wechat extends Migration
{
    public function up()
    {
        $this->alterColumn('wechat', 'access_token', $this->string(255));
    }

    public function down()
    {
        $this->alterColumn('wechat', 'access_token', $this->string(255)->notNull()->defaultValue(''));
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
