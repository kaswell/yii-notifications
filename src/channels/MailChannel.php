<?php

namespace kaswell\notification\channels;


use kaswell\notification\NotifiableInterface;
use kaswell\notification\NotificationInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use Yii;

class MailChannel extends Component implements ChannelInterface
{
    public $mailer = 'mailer';

    public $from;

    public function init()
    {
        parent::init();
        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\MailerInterface');
    }
    
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        $message = $notification->exportFor('mail');

        return $this->mailer->compose()
            ->setFrom(isset($message->from) ? $message->from : $this->from)
            ->setTo($recipient->routeNotificationFor('mail'))
            ->setSubject($message->subject)
            ->setHtmlBody($message->view)
            ->send();
    }
}
