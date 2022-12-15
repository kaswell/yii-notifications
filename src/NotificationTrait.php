<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification;

use kaswell\notification\messages\AbstractMessage;
use yii\helpers\Inflector;

trait NotificationTrait
{
    public function broadcastOn(): array
    {
        $channels = [];
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (strpos($method, 'exportFor') === false) {
                continue;
            }
            $channel = str_replace('exportFor', '', $method);
            if (!empty($channel)) {
                $channels[] = Inflector::camel2id($channel);
            }
        }
        return $channels;
    }

    public function exportFor($channel)
    {
        if (method_exists($this, $method = 'exportFor'.Inflector::id2camel($channel))) {
            return $this->{$method}();
        }
        throw new \InvalidArgumentException("Can not find message export for chanel `{$channel}`");
    }

}