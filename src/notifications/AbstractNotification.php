<?php

namespace kaswell\notification\notifications;

use kaswell\notification\messages\DatabaseMessage;
use kaswell\notification\models\Text;
use kaswell\notification\NotificationInterface;
use kaswell\notification\NotificationTrait;

abstract class AbstractNotification implements NotificationInterface
{
    use NotificationTrait;

    public $object;
    public array $data = [];
    public $event;

    public function __construct($object, array $data = [])
    {
        $this->object = $object;
        $this->data = $data;
    }

    public function exportForDatabase()
    {
        return \Yii::createObject([
            'class' => DatabaseMessage::class,
            'subject' => get_class($this->object) . " object has been " . static::$message,
            'body' => $this->getText(),
            'data' => $this->data,
        ]);
    }

    protected function getText(): string
    {
        return Text::find()->where(['object' => get_class($this->object), 'event' => $this->event ?? ''])->one()->text
            ?? get_class($this->object) . " new object #{$this->object->id} has been " . static::$message;
    }
}