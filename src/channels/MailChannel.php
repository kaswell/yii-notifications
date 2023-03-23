<?php

namespace kaswell\notification\channels;


use kaswell\notification\NotifiableInterface;
use kaswell\notification\NotificationInterface;
use yii\base\Component;
use yii\di\Instance;

class MailChannel extends Component implements ChannelInterface
{
    /** @var string|\yii\mail\MailerInterface' */
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

        return \Yii::$app->mailer->compose()
            ->setHtmlBody($message->view)
            ->setFrom(isset($message->from) ? $message->from : $this->from)
            ->setTo($recipient->routeNotificationFor('mail'))
            ->setSubject($message->subject)
            ->send();
    }
}
