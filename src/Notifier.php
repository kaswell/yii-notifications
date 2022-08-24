<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification;

use kaswell\notification\channels\ChannelInterface;
use kaswell\notification\events\NotificationEvent;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Notifier extends Component
{
    const EVENT_AFTER_SEND = 'afterSend';

    public $channels = [];

    public function send($recipients, $notifications): void
    {
        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }
        
        if (!is_array($notifications)){
            $notifications = [$notifications];
        }
        
        foreach ($recipients as $recipient) {
            $channels = array_intersect($recipient->viaChannels(), array_keys($this->channels));
            foreach ($notifications as $notification) {
                if (!$recipient->shouldReceiveNotification($notification)) {
                    continue;
                }

                $channels = array_intersect($channels, $notification->broadcastOn());
                foreach ($channels as $channel) {
                    $channelInstance = $this->getChannelInstance($channel);
                    try {
                        \Yii::info("Sending notification " . get_class($notification) . " to " . get_class($recipient) . " via {$channel}", __METHOD__);
                        $response = $channelInstance->send($recipient, $notification);
                    } catch (\Exception $e) {
                        $response = $e;
                    }
                    $this->trigger(self::EVENT_AFTER_SEND, new NotificationEvent([
                        'notification' => $notification,
                        'recipient' => $recipient,
                        'channel' => $channel,
                        'response' => $response
                    ]));
                }
            }
        }
    }


    protected function getChannelInstance($channel)
    {
        if (!isset($this->channels[$channel])) {
            throw new InvalidConfigException("Notification channel `{$channel}` is not available or configuration is missing");
        }
        if (!$this->channels[$channel] instanceof ChannelInterface) {
            $this->channels[$channel] = \Yii::createObject($this->channels[$channel]);
        }
        return $this->channels[$channel];
    }
}