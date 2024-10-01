<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag\utility
 * @category   CategoryName
 */

namespace open20\amos\tag\utility;

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\record\Record;
use open20\amos\core\utilities\CoreCommonUtility;
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
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
        $query = Tag::find()
            ->innerJoinWith('entitysTagsMms')
            ->andWhere([$entityTagsMmTable . '.classname' => $className])
            ->andWhere([$entityTagsMmTable . '.record_id' => $modelId])
            ->andWhere([Tag::tableName() . '.deleted_at' => null]);
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
        $query->select([$tagTable . '.id'])
            ->from([$tagTable])
            ->innerJoin(
                $entityTagsMmTable,
                '`' . $tagTable . '`.`id` = `' . $entityTagsMmTable . '`.`tag_id`'
            )
            ->andWhere([$tagTable . '.deleted_at' => null])
            ->andWhere([$entityTagsMmTable . '.deleted_at' => null])
            ->andWhere([$entityTagsMmTable . '.classname' => $className])
            ->andWhere([$entityTagsMmTable . '.record_id' => $modelId]);
        $tagIds = $query->column();
        
        return $tagIds;
    }

    /**
     * @param Record $model
     * @param int $rootId
     * @return array|Tag[]|ActiveRecord[]
     * @throws InvalidConfigException
     */
    public static function findEntityTagsMm($model, $rootId)
    {
        $model_classname_id = 0;
        $modelsclassname = ModelsClassname::find()->andWhere(['classname' => $model->className()])->one();
        if(!is_null($modelsclassname))
        {
            $model_classname_id = $modelsclassname->id;
        }
        $entityTagsMmTable = EntitysTagsMm::tableName();
        /** @var ActiveQuery $query */
        $query = EntitysTagsMm::find();
        $query->innerJoinWith('tag');
        if($model_classname_id)
        {
            $query->andWhere([$entityTagsMmTable . '.models_classname_id' => $model_classname_id]);
        }
        else
        {
            $query->andWhere(['classname' => $model->className()]);
        }
        $query->andWhere([$entityTagsMmTable . '.record_id' => $model->id]);
        $query->andWhere([$entityTagsMmTable . '.root_id' => $rootId]);
        $entityTagsMms = $query->all();
        
        return $entityTagsMms;
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
        $query->select(['root'])
            ->from($tagTable)
            ->andWhere(['deleted_at' => null])
            ->groupBy(['root']);
        $rootIds = $query->column();

        return $rootIds;
    }

    /**
     * This method returns all tags of a tree.
     * @param Tag $treeTag
     * @param bool $onlyIds
     * @return array
     */
    public static function findTreeTags($treeTag, $onlyIds = false)
    {
        $children = $treeTag->children()->all();
        if (!$onlyIds) {
            return $children;
        }

        $childrenIds = [];
        foreach ($children as $child) {
            /** @var Tag $child */
            $childrenIds[$child->id] = $child->id;
        }

        return $childrenIds;
    }

    /**
     * 
     * @param integer $rootId
     * @param bool $onlyIds
     * @return array
     */
    public static function findTagsByRootId($rootId, $onlyIds = false)
    {
        $childrenIds = [];
        $tagRoot = Tag::findOne(['root' => $rootId]);
        if (!is_null($tagRoot)) {
            $children = $tagRoot->children()->all();
            if (!$onlyIds) {
                return $children;
            }

            foreach ($children as $child) {
                /** @var Tag $child */
                $childrenIds[$child->id] = $child->id;
            }
        }
        return $childrenIds;
    }

    /**
     * @param string $name
     * @param Tag|null $parent
     * @return Tag|null
     */
    public static function createTagByName($name, $parent = null)
    {
        $node = new Tag();
        $node->nome = $name;
        $node->codice = '';
        $node->descrizione = '';
        $node->icon = '';
        $node->icon_type = 1;
        $node->active = 1;
        $node->activeOrig = $node->active;
        $node->selected = 0;
        $node->disabled = 0;
        $node->readonly = 0;
        $node->visible = 1;
        $node->collapsed = 0;
        $node->movable_u = 1;
        $node->movable_d = 1;
        $node->movable_l = 1;
        $node->movable_r = 1;
        $node->removable = 1;
        $node->removable_all = 0;
        if (!is_null($parent)) {
            $node->appendTo($parent);
        }

        if (!$node->save()) {
            return null;
        }

        return $node;
    }

    /**
     * This method creates a new entry in EntitysTagsMm and checks
     * if there's already the same entry before add.
     * @param Record $model
     * @param Tag $tag
     * @return bool
     */
    public static function createEntityTagsMm($model, $tag)
    {
        $model_classname_id = 0;
        $modelsclassname = ModelsClassname::find()->andWhere(['classname' => $model->className()])->one();
        if(!is_null($modelsclassname))
        {
            $model_classname_id = $modelsclassname->id;
        }
        /** @var ActiveQuery $query */
        $query = EntitysTagsMm::find();
        $query->andWhere(['root_id' => $tag->root]);
        $query->andWhere(['record_id' => $model->id]);
        $query->andWhere(['tag_id' => $tag->id]);
        if($model_classname_id)
        {
            $query->andWhere(['models_classname_id' => $model_classname_id]);
        }
        else
        {
            $query->andWhere(['classname' => $model->className()]);
        }
        $entityTagsMms = $query->all();

        $ok = true;
        if (!count($entityTagsMms)) {
            $entityTagsMm = new EntitysTagsMm();
            $entityTagsMm->classname = $model->className();
            $entityTagsMm->models_classname_id = $model_classname_id;
            $entityTagsMm->record_id = $model->id;
            $entityTagsMm->tag_id = $tag->id;
            $entityTagsMm->root_id = $tag->root;
            $ok = $entityTagsMm->save();
        }

        return $ok;
    }

    /**
     * @param Record $model
     * @param int $rootId
     * @return bool
     * @throws InvalidConfigException
     */
    public static function deleteEntityTagsMms($model, $rootId)
    {
        $entityTagsMms = static::findEntityTagsMm($model, $rootId);
        foreach ($entityTagsMms as $entityTagsMm) {
            $entityTagsMm->delete();
            if ($entityTagsMm->hasErrors()) {
                CoreCommonUtility::printErrorMessage(
                    AmosTag::t('amostag', '#error_delete_entity_tags_mm')
                );
                
                return false;
            }
        }

        return true;
    }

    /**
     * This method is useful to add tags to a tree.
     * @param Tag $rooTag
     * @param array $tagsToInsertArray
     * @return bool
     */
    public static function insertTagsToRootByArray($rooTag, $tagsToInsertArray)
    {
        foreach ($tagsToInsertArray as $branchName => $tagNames) {
            $branchTag = self::createTagByName($branchName, $rooTag);
            if (is_null($branchTag)) {
                MigrationCommon::printConsoleMessage('Errore nella creazione del tag branch ' . $branchName);
                return false;
            }

            foreach ($tagNames as $tagName) {
                $tag = self::createTagByName($tagName, $branchTag);
                if (is_null($tag)) {
                    MigrationCommon::printConsoleMessage('Errore nella creazione del tag nodo' . $tagName);
                    return false;
                }
            }
        }

        return true;
    }

}