<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\migrations;

use kaswell\notification\models\Notification;
use yii\db\Migration;

class m060822_000001_create_notification_table extends Migration
{
    public function up()
    {
        $this->createTable(Notification::tableName(), [
            'id' => $this->primaryKey(),
            'level' => $this->string(),
            'notifiable_type' => $this->string(),
            'notifiable_id' => $this->integer()->unsigned(),
            'subject' => $this->string(),
            'body' => $this->text(),
            'read_at' => $this->timestamp()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null(),
        ]);
        $this->createIndex('notifiable', Notification::tableName(), ['notifiable_type', 'notifiable_id']);
    }

    public function down()
    {
        $this->dropIndex('notifiable', Notification::tableName());
        $this->dropTable(Notification::tableName());
    }

}