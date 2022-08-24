<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\migrations;

use kaswell\notification\models\Text;
use yii\db\Migration;

class m060822_000003_create_notification_texts_table extends Migration
{
    public function up()
    {
        $this->createTable(Text::tableName(), [
            'object' => $this->string(),
            'event' => $this->string(),
            'text' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null(),
        ]);
    }

    public function down()
    {
        $this->dropTable(Text::tableName());
    }

}