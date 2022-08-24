<?php
/*
 * Copyright (c) 2022.
 * @author Kasatkin (Kaswell) Aliaksandr  <a.kasatkin@nonium.by>
 */

namespace kaswell\notification\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecordInterface;

class ReadableBehavior extends Behavior
{
    public $readAttribute = 'read_at';


    public function markAsRead()
    {
        /** @var ActiveRecordInterface $model */
        $model = $this->owner;
        if (is_null($model->{$this->readAttribute})) {
            $model->{$this->readAttribute} = date('Y-m-d H:i:s');
            $model->update(false, [$this->readAttribute]);
        }
    }


    public function markAsUnread(): void
    {
        /** @var ActiveRecordInterface $model */
        $model = $this->owner;
        if (!is_null($model->{$this->readAttribute})) {
            $model->{$this->readAttribute} = null;
            $model->update(false, [$this->readAttribute]);
        }
    }


    public function isRead(): bool
    {
        return $this->owner->{$this->readAttribute} !== null;
    }


    public function isUnread(): bool
    {
        return $this->owner->{$this->readAttribute} === null;
    }
}
