<?php

use yii\db\Migration;

class m161119_131015_create_table_request extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('request', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'email' => $this->string(),
            'age' => $this->integer(),
            'height' => $this->integer(),
            'weight' => $this->integer(),
            'rent_equipment' => $this->integer(),
            'english_level' => $this->integer(),
            'city' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('currency');
    }
}
