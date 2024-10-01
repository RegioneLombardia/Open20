<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\cwh\models\CwhTagInterestMm;
use open20\amos\tag\models\Tag;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class UserProfileTagWidget
 * @package open20\amos\cwh\widgets
 */
class UserProfileTagInterestsWidget extends \yii\widgets\InputWidget
{
    public $form;
    public $contentsTrees = [];
    public $contentsTreesSimple = [];
    public $contentClass = '';
    public $userProfileTags = [];

    /**
     * @inheritdoc
     */
    public $name = 'interestTagValues';

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->contentClass = AmosAdmin::instance()->model('UserProfile');
        $this->fetchContentsTrees();
    }

    /**
     * Return all roots
     * @return void
     * @throws InvalidConfigException
     */
    private function fetchContentsTrees()
    {
        $moduleTag = Yii::$app->getModule('tag');
        if (isset($moduleTag)) {
            $query = Tag::find()->andWhere([Tag::tableName() . '.lvl' => 0,]);
            $this->userProfileTags = CwhTagInterestMm::find()
                ->andWhere(['classname' => UserProfile::className()])
                ->select('tag_id')
                ->distinct()
                ->column();

            if (!empty($this->userProfileTags)) {
                foreach ($this->userProfileTags as $tagId) {
                    $tagExists = Tag::findOne($tagId);
                    if ($tagExists) {
                        $query->andWhere(['in', Tag::tableName() . '.root', $tagId]);
                    }
                }
            }

            if ($query->count()) {
                $this->contentsTreesSimple = $this->contentsTreesSimple + ArrayHelper::map($query->all(), 'id', 'nome');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $moduleTag = Yii::$app->getModule('tag');
        if (isset($moduleTag)) {
            return $this->render('tag', [
                'model' => $this->model,
                'form' => $this->form,
                'name' => $this->name,
                'contentsTrees' => $this->contentsTrees,
                'contentsTreesSimple' => $this->contentsTreesSimple,
                'limit_trees' => $this->getLimitTrees()
            ]);
        }
        return '';
    }

    /**
     * @param $tagId
     * @return Tag
     */
    private function getTagById($tagId)
    {
        return Tag::findOne($tagId);
    }

    /**
     * @return array
     */
    private function getLimitTrees()
    {
        $array_limit_trees = [];

        foreach ($this->contentsTreesSimple as $id_tree => $label_tree) {
            //limite di default: nessun limite
            $limit_tree = false;

            //carica il nodo radice
            $root_node = $this->getTagById($id_tree);

            //se è presente un limite impostato per questa radice allora lo usa
            if ($root_node->limit_selected_tag && is_numeric($root_node->limit_selected_tag)) {
                $limit_tree = $root_node->limit_selected_tag;
            }

            $array_limit_trees["tree_" . $id_tree] = $limit_tree;
        }

        return $array_limit_trees;
    }

}
