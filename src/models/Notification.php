<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\models;


use kaswell\notification\behaviors\ReadableBehavior;
use kaswell\notification\messages\DatabaseMessage;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Database notification model
 * @property string $level
 * @property string $subject
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $body
 * @property string $data
 * @property DatabaseMessage $message
 * @property string $read_at
 * @property $notifiable
 * @method  void markAsRead()
 * @method  void markAsUnread()
 * @method  bool read()
 * @method  bool unread()
 */
class Notification extends ActiveRecord
{

    public static function tableName(): string
    {
        return '{{%notifications}}';
    }


    public function rules(): array
    {
        return [
            [['level', 'notifiable_type', 'subject', 'body', 'data'], 'string'],
            ['notifiable_id', 'integer'],
        ];
    }


    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
            ReadableBehavior::class
        ];
    }


    public function getNotifiable(): ActiveQuery
    {
        return $this->hasOne($this->notifiable_type, ['id' => 'notifiable_id']);
    }


    /**
     * @param null|string|\Closure|array $key
     * @return mixed
     */
    public function data($key = null)
    {
        $data = Json::decode($this->data);
        if ($key === null) {
            return $data;
        }
        return ArrayHelper::getValue($data, $key);
    }

}