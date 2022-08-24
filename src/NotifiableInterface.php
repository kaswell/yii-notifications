<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification;

interface NotifiableInterface
{
    /**
     * @param NotificationInterface $notification
     * @return bool
     */
    public function shouldReceiveNotification(NotificationInterface $notification);

    /**
     * @return array
     */
    public function viaChannels();

    /**
     * @param string $channel
     * @return mixed
     */
    public function routeNotificationFor($channel);
}