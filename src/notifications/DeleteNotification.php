<?php

namespace kaswell\notification\notifications;


use yii\db\ActiveRecord;

class DeleteNotification extends AbstractNotification
{
    protected const EVENT = ActiveRecord::EVENT_BEFORE_DELETE;

    protected static $message = 'deleted';
}