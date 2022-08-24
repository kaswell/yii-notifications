<?php

namespace kaswell\notification\notifications;

use yii\db\ActiveRecord;

class InsertNotification extends AbstractNotification
{
    protected const EVENT = ActiveRecord::EVENT_AFTER_INSERT;

    protected static $message = 'inserted';
}