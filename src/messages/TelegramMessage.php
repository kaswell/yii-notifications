<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\messages;

class TelegramMessage extends AbstractMessage
{
    public $replyToMessageId;

    public $replyMarkup;

    public $silentMode = false;

    public $withoutPagePreview = false;
}