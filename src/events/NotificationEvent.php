<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\events;

use kaswell\notification\NotifiableInterface;
use kaswell\notification\NotificationInterface;
use yii\base\Event;

class NotificationEvent extends Event
{
    /**
     * @var NotificationInterface
     */
    public $notification;

    /**
     * @var NotifiableInterface
     */
    public $recipient;

    /**
     * @var string
     */
    public $channel;

    /**
     * @var mixed
     */
    public $response;
}