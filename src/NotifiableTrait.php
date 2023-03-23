<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification;

use kaswell\notification\models\Notification;
use yii\db\BaseActiveRecord;
use yii\helpers\Inflector;

trait NotifiableTrait
{
    /**
     * @param NotificationInterface $notification
     * @return bool
     */
    public function shouldReceiveNotification(NotificationInterface $notification): bool
    {
        $alias = Inflector::camel2id(get_class($notification));
        if (isset($this->notificationSettings)) {
            $settings = $this->notificationSettings;
            if (array_key_exists($alias, $settings)) {
                if ($settings[$alias] instanceof \Closure) {
                    return call_user_func($settings[$alias], $notification);
                }
                return (bool) $settings[$alias];
            }
        }
        return true;
    }

    public function viaChannels()
    {
        return ['database', 'mail'];
    }

    /**
     * ```php
     * public function routeNotificationForMail() {
     *      return $this->email;
     * }
     * ```
     * @param $channel string
     * @return mixed
     */
    public function routeNotificationFor($channel)
    {
        if (method_exists($this, $method = 'routeNotificationFor'.Inflector::id2camel($channel))) {
            return $this->{$method}();
        }
        switch ($channel) {
            case 'mail':
                return $this->email;
            case 'database':
                return [get_class($this), $this->id];
        }
    }

    public function getNotifications()
    {
        /** @var $this BaseActiveRecord */
        return $this->hasMany(Notification::class, ['notifiable_id' => 'id'])
            ->andOnCondition(['notifiable_type' => get_class($this)]);
    }

    public function getUnreadNotifications()
    {
        return $this->getNotifications()->andOnCondition(['read_at' => null]);
    }
}