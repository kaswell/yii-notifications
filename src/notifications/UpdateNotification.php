<?php

namespace kaswell\notification\notifications;

use yii\db\ActiveRecord;

class UpdateNotification extends AbstractNotification
{
    protected const EVENT = ActiveRecord::EVENT_AFTER_UPDATE;

    protected static $message = 'updated';
}