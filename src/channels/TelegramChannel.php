<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\channels;

use kaswell\notification\NotifiableInterface;
use kaswell\notification\NotificationInterface;
use kaswell\notification\messages\TelegramMessage;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\Json;
use yii\httpclient\Client;

class TelegramChannel extends Component implements ChannelInterface
{
    /**
     * @var Client|array|string
     */
    public $httpClient;

    /**
     * @var string
     */
    public $apiUrl = "https://api.telegram.org";

    /**
     * @var string
     */
    public $botToken;

    /**
     * @var string
     */
    public $parseMode = self::PARSE_MODE_MARKDOWN;

    const PARSE_MODE_HTML = "HTML";

    const PARSE_MODE_MARKDOWN = "Markdown";

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if(!isset($this->botToken)){
            throw new InvalidConfigException('Bot token is undefined');
        }

        if (!isset($this->httpClient)) {
            $this->httpClient = [
                'class' => Client::class,
                'baseUrl' => $this->apiUrl,
            ];
        }
        $this->httpClient = Instance::ensure($this->httpClient, Client::class);
    }


    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /** @var TelegramMessage $message */
        $message = $notification->exportFor('telegram');
        $text = $message->body;
        if (!empty($message->subject)) {
            $text = "*{$message->subject}*\n{$message->body}";
        }
        $chatId = $recipient->routeNotificationFor('telegram');
        if(!$chatId){
            throw new InvalidArgumentException( 'No chat ID provided');
        }

        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'disable_notification' => $message->silentMode,
            'disable_web_page_preview' => $message->withoutPagePreview,
        ];

        if ($message->replyToMessageId) {
            $data['reply_to_message_id'] = $message->replyToMessageId;
        }

        if ($message->replyMarkup) {
            $data['reply_markup'] = Json::encode($message->replyMarkup);
        }

        if(isset($this->parseMode)){
            $data['parse_mode'] = $this->parseMode;
        }

        return $this->httpClient->createRequest()
            ->setUrl($this->createUrl())
            ->setData($data)
            ->send();
    }

    private function createUrl()
    {
        return "bot{$this->botToken}/sendMessage";
    }
}