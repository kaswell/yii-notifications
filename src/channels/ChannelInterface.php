<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\channels;

use kaswell\notification\NotifiableInterface;
use kaswell\notification\NotificationInterface;
use Exception;

interface ChannelInterface
{
    /**
     * @param NotifiableInterface $recipient
     * @param NotificationInterface $notification
     * @return mixed
     * @throws Exception
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification);
}