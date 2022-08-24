<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */
namespace kaswell\notification\messages;


use yii\base\BaseObject;

abstract class AbstractMessage extends BaseObject
{
    /**
     * The "level" of the notification (info, success, error).
     * @var string
     */
    public $level = 'info';

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $body;
}