<?php

use yii\db\Migration;

class m160322_081057_create_table_wechat_auto_reply extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('wechat_auto_reply', [
            'id' => $this->primaryKey(),
            'wid' => $this->integer(10)->unsigned()->notNull(),
            'key_words'=>$this->string()->notNull()->defaultValue(''),
            'comment' => $this->text()->notNull()->defaultValue(''),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0),
            'updated_at' => $this->integer(11)->notNull()->defaultValue(0),
        ],$tableOptions);
    }

    public function down()
    {
        $this->dropTable('wechat_auto_reply');
    }
}
