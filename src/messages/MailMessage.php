<?php

namespace kaswell\notification\messages;


class MailMessage extends AbstractMessage
{
    /**
     * @var string|array|null $view 
     */
    public $view;

    /**
     * @var array
     */
    public $viewData;

    /**
     * @var string
     */
    public $from;
}