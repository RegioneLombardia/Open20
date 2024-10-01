<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package     open20\amos\tag\widgets
 * @category   CategoryName
 */

namespace open20\amos\tag\widgets;

use open20\amos\core\record\Record;
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\Tag;
use open20\amos\tag\models\TagModelsAuthItemsMm;
use yii\base\Widget;

/**
 * Class ShowTagsWidget
 * @package open20\amos\tag\widgets
 */
class ShowTagsWidget extends Widget
{

    /**
     * @var Record $model
     */
    public $model;

    /**
     * @var integer $rootId
     */
    public $rootId;

    /**
     * @var array $rootIdsArray
     */
    public $rootIdsArray = [];


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->rootId) {
            $this->rootIdsArray[] = $this->rootId;
        }

        if (empty($this->rootIdsArray)) {
            $this->rootIdsArray = TagModelsAuthItemsMm::find()
                ->andWhere(['classname' => get_class($this->model)])
                ->groupBy('tag_id')
                ->addSelect('tag_id')
                ->column();
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!count($this->rootIdsArray)) {
            return AmosTag::t('amostag', '#no_tree_enabled');
        }

        if(!empty( $this->model->getTagValues())) {
            $tagValues = explode(',', $this->model->getTagValues());
        }

        foreach ($this->rootIdsArray as $rootId) {
            $root = Tag::findOne($rootId);
            if(isset($tagValues)) {
                $tagsQuery = Tag::find()
                    ->andWhere(['root' => $rootId, 'id' => $tagValues])
                    ->andWhere(['<>', 'tag.id', 'tag.root'])
                    ->orderBy('lft,rgt');
                $tags = $tagsQuery->all();
            } else {
                $tags = [];
            }

            echo $this->render('show-tags-widget', [
                'root' => $root,
                'tags' => $tags
            ]);
        }

        return null;
    }

}
