<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\migrations;

use kaswell\notification\models\Notification;
use yii\db\Migration;

class m060822_000002_add_data_column extends Migration
{

    public function up()
    {
        $this->addColumn(Notification::tableName(), 'data', $this->text());
    }

    public function down()
    {
        $this->dropColumn(Notification::tableName(), 'data');
    }

}