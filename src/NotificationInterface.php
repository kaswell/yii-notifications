<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification;

use kaswell\notification\messages\AbstractMessage;

interface NotificationInterface
{
    /**
     * @param string $channel
     * @return AbstractMessage
     */
    public function exportFor($channel);

    /**
     * @return array
     */
    public function broadcastOn();
}