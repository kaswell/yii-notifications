<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\behaviors;

use kaswell\notification\NotifiableInterface;
use kaswell\notification\NotificationInterface;
use kaswell\notification\Notifier;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\di\Instance;

class NotifiableBehavior extends Behavior
{
    public $notifications = [];

    /**
     * @var Notifier
     */
    public $notifier = 'notifier';


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->notifier = Instance::ensure($this->notifier, Notifier::class);
    }


    public function attach($owner)
    {
        $this->owner = $owner;
        foreach ($this->notifications as $event => $notifications) {
            if (!is_array($notifications)) {
                $notifications = [$notifications];
            }
            foreach ($notifications as $notification) {
                $owner->on($event, [$this, 'handle'], ['notification' => $notification]);
            }
        }
    }


    public function detach()
    {
        if ($this->owner) {
            foreach ($this->notifications as $event => $notifications) {
                if (!is_array($notifications)) {
                    $notifications = [$notifications];
                }
                foreach ($notifications as $notification) {
                    $this->owner->off($event, [$this, 'handle']);
                }
            }
            $this->owner = null;
        }
    }

    /**
     * @param Event $event
     * @throws InvalidConfigException
     */
    public function handle(Event $event)
    {
        if (!isset($event->data['notification'])) {
            throw new \InvalidArgumentException('Can not find `notification` in event data');
        }
        if (!$this->owner instanceof NotifiableInterface) {
            throw new \RuntimeException('Owner should implement `NotifiableInterface`');
        }

        $notification = $event->data['notification'];
        $config = [];

        foreach (get_object_vars($event) as $param => $value) {
            $config[$param] = $value;
        }
        $config['class'] = $notification;

        /** @var $notification NotificationInterface */
        $notification = \Yii::createObject($config);
        $this->notifier->send($this->owner, $notification);
    }
}