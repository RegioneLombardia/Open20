<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag\utility
 * @category   CategoryName
 */

namespace open20\amos\tag\utility;

use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use yii\base\BaseObject;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class TagUtility
 * @package open20\amos\tag\utility
 */
class TagUtility extends BaseObject
{
    /**
     * This method returns all tags selected for a model.
     * @param string $className
     * @param int $modelId
     * @return Tag[]
     */
    public static function findTagsByModel($className, $modelId)
    {
        $entityTagsMmTable = EntitysTagsMm::tableName();
        /** @var ActiveQuery $query */
        $query = Tag::find();
        $query->innerJoinWith('entitysTagsMms');
        $query->andWhere([$entityTagsMmTable . '.classname' => $className]);
        $query->andWhere([$entityTagsMmTable . '.record_id' => $modelId]);
        $query->andWhere([Tag::tableName() . '.deleted_at' => null]);
        $tags = $query->all();
        return $tags;
    }

    /**
     * This method returns all tag ids selected for a model.
     * @param string $className
     * @param int $modelId
     * @return Tag[]
     */
    public static function findTagIdsByModel($className, $modelId)
    {
        $tagTable = Tag::tableName();
        $entityTagsMmTable = EntitysTagsMm::tableName();
        $query = new Query();
        $query->select([$tagTable . '.id']);
        $query->from([$tagTable]);
        $query->innerJoin($entityTagsMmTable, '`' . $tagTable . '`.`id` = `' . $entityTagsMmTable . '`.`tag_id`');
        $query->andWhere([$tagTable . '.deleted_at' => null]);
        $query->andWhere([$entityTagsMmTable . '.deleted_at' => null]);
        $query->andWhere([$entityTagsMmTable . '.classname' => $className]);
        $query->andWhere([$entityTagsMmTable . '.record_id' => $modelId]);
        $tagIds = $query->column();
        return $tagIds;
    }

    /**
     * This method returns all root tags.
     * @return Tag[]
     */
    public static function findAllRootTags()
    {
        /** @var ActiveQuery $query */
        $query = Tag::find();
        $query->groupBy(['root']);
        $rootTags = $query->all();
        return $rootTags;
    }

    /**
     * This method returns all root tag ids.
     * @return array
     */
    public static function findAllRootTagIds()
    {
        $tagTable = Tag::tableName();
        $query = new Query();
        $query->select(['root']);
        $query->from($tagTable);
        $query->andWhere(['deleted_at' => null]);
        $query->groupBy(['root']);
        $rootIds = $query->column();
        return $rootIds;
    }
}
