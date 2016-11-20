<?php

use yii\db\Migration;

class m161120_150257_create_table_request_photo extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('request_photo', [
            'id' => $this->primaryKey(),
            'request_id' => $this->integer()->notNull(),
            'url' => $this->string()
        ], $tableOptions);

        $this->addForeignKey('fk_r_ph_request', 'request_photo', 'request_id', 'request', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('request_photo');
    }
}
