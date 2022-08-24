<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\behaviors;

use kaswell\notification\notifications\InsertNotification;
use kaswell\notification\notifications\UpdateNotification;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;
use Yii;

class ActiveRecordBehavior extends Behavior
{
    public $excludedAttributes = [];

    public $notificationClasses = [
        ActiveRecord::EVENT_AFTER_INSERT => InsertNotification::class,
        ActiveRecord::EVENT_AFTER_UPDATE => UpdateNotification::class,
    ];

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'sendNotification',
            ActiveRecord::EVENT_AFTER_UPDATE => 'sendNotification',
            ActiveRecord::EVENT_BEFORE_DELETE => 'sendDeleteNotification',
        ];
    }


    public function sendNotification(Event $event): void
    {
        /** @var ActiveRecord $owner */
        $owner = $this->owner;
        $changedAttributes = $event->changedAttributes;

        $diff = [];

        foreach ($changedAttributes as $attrName => $attrVal) {
            $newAttrVal = $owner->getAttribute($attrName);

            $newAttrVal = is_float($newAttrVal) ? StringHelper::floatToString($newAttrVal) : $newAttrVal;
            $attrVal = is_float($attrVal) ? StringHelper::floatToString($attrVal) : $attrVal;

            if ($newAttrVal != $attrVal) {
                $diff[$attrName] = [$attrVal, $newAttrVal];
            }
        }

        $diff = $this->applyExclude($diff);

        if ($diff) {
            $diff = $this->owner->setChangelogLabels($diff);

            $eventNotification = \Yii::createObject([
                'class' => $this->getNotificationClass($event->name),
                'object' => $owner,
                'data' => $diff,
            ]);

            Yii::$app->notifier->send($recipient, $eventNotification);
        }
    }

    private function getNotificationClass($name): string
    {
        return $this->notificationClasses[$name];
    }


    private function applyExclude(array $diff)
    {
        foreach ($this->excludedAttributes as $attr) {
            unset($diff[$attr]);
        }

        return $diff;
    }


    public function sendDeleteNotification()
    {

    }
}