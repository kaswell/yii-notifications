<?php

use yii\db\Migration;

class m010101_100001_create_notifications_tables extends Migration
{
    /**
     * Create table `notifications`
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // notifications
        $this->createTable('{{%notifications}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);
        $this->createIndex('user_id_index', '{{%notifications}}', ['user_id']);
        $this->createIndex('created_at_index', '{{%notifications}}', ['created_at']);
    }
}