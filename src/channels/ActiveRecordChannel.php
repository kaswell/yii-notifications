<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\channels;

use kaswell\notification\messages\DatabaseMessage;
use kaswell\notification\models\Notification;
use kaswell\notification\NotifiableInterface;
use kaswell\notification\NotificationInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\helpers\Json;

class ActiveRecordChannel extends Component implements ChannelInterface
{
    /**
     * @var BaseActiveRecord|string
     */
    public $model = Notification::class;

    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        $model = \Yii::createObject($this->model);

        if (!$model instanceof BaseActiveRecord) {
            throw new InvalidConfigException('Model class must extend from ' . BaseActiveRecord::class);
        }

        /** @var DatabaseMessage $message */
        $message = $notification->exportFor('database');
        list($notifiableType, $notifiableId) = $recipient->routeNotificationFor('database');

        $data = [
            'level' => $message->level,
            'subject' => $message->subject,
            'body' => $message->body,
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
            'data' => Json::encode($message->data),
        ];

        if ($model->load($data, '')) {
            return $model->insert();
        }

        return false;
    }
}