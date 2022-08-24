<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\models;

use yii\console\Application;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use kaswell\notification\helpers\CompositeRelationHelper;

class Text extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%notification_texts}}';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['object', 'event', 'text'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object' => 'Object',
            'event' => 'Event',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (empty($this->userId) && !(\Yii::$app instanceof Application) && !\Yii::$app->user->isGuest) {
            $this->userId = \Yii::$app->user->id;
        }

        if (empty($this->hostname) && \Yii::$app->request->hasMethod('getUserIP')) {
            $this->hostname = \Yii::$app->request->getUserIP();
        }

        if (!empty($this->data) && is_array($this->data)) {
            $this->data = json_encode($this->data);
        }

        if ($this->relatedObject) {
            $this->relatedObjectType = CompositeRelationHelper::resolveObjectType($this->relatedObject);
            $this->relatedObjectId = $this->relatedObject->primaryKey;
        }

        return parent::beforeSave($insert);
    }
}