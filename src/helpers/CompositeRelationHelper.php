<?php

namespace kaswell\notification\helpers;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class CompositeRelationHelper
{
    public static function resolveObjectType(ActiveRecord $model): string
    {
        $reflection = new \ReflectionClass($model);
        return $reflection->getShortName();
    }


    public static function relatedObject($objectType, $objectId, string $namespace = 'common\\models'): ?ActiveRecord
    {
        if (!$objectType) {
            return null;
        }

        return self::relatedObjectQuery($objectType, $objectId, $namespace)->one();
    }


    public static function relatedObjectQuery($objectType, $objectId, string $namespace = 'common\\models'): ?ActiveQuery
    {
        if (!$objectType) {
            return null;
        }

        $class = Inflector::classify($objectType);

        /** @var ActiveRecord $className */
        $className = rtrim($namespace, '\\') . "\\" . $class;
        $query = $className::find()->andWhere(['id' => $objectId]);

        return $query;
    }


    public static function relatedObjectLink($objectType, $objectId): array
    {
        $path = Inflector::camel2id(Inflector::camelize($objectType));
        $path .= '/view';

        return ['/' . $path, 'id' => $objectId];
    }


    public static function relatedObjectName($objectType, $objectId): string
    {
        $class = Inflector::classify($objectType);
        $objectName = $class . ' #' . $objectId;

        return $objectName;
    }
}