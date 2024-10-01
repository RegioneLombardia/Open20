<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag\widgets
 * @category   CategoryName
 * 
 */

namespace open20\amos\tag\widgets;

use yii\widgets\InputWidget;
use open20\amos\tag\models\Tag;
use yii\helpers\ArrayHelper;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;

/**
 * 
 */
class TreeTagWidget extends InputWidget
{

    /**
     * Form
     *
     * @var [type]
     */
    public $form = null;

    /**
     * Trees Tags
     *
     * @var array | model | open20\amos\tag\models\Tag
     */
    public $trees = null;

    /**
     * List Trees Tags
     *
     * @var array | model | open20\amos\tag\models\Tag
     */
    public $tags = null;

    /**
     * Trees Tags Filter
     * 
     * "owner-interest" 
     * "own-content-created"
     * "all-in-content"
     *
     * @var string
     */
    public $filter = "all";

    /**
     * Widget View Name
     *
     * @var string
     */
    public $view_name = "tree_tag_widget";

    /**
     * Widget Label
     *
     * @var string
     */
    public $label = 'Tag Aree di Interesse';

    /**
     * Init
     *
     * @return void
     */
    public function init()
    {

        parent::init();

        // set Trees
        $this->trees = $this->trees ?? $this->getTrees();
    }

    /**
     * Run
     *
     * @return render view
     */
    public function run()
    {

        return $this->render($this->view_name, [
            'id' => $this->id,
            'model' => $this->model,
            'form' => $this->form,
            'name' => $this->name,
            'options' => $this->options,
            'label' => $this->label,
            'data' => $this->getRootAndId(),
        ]);
    }

    /**
     * Method to return trees tag for the model and the parent model
     *
     * @return array | model | open20\amos\tag\models\Tag
     */
    private function getTrees()
    {
        $trees = $this->queryGetTrees()
            ->orderBy(['tag.nome' => SORT_DESC])
            ->all();

        return $trees;
    }

    /**
     * Method to get the query for extracting Trees Tags nodes for the model
     * and the parent model
     * from table tag join tagModelsAuthItems
     *
     * @return query
     */
    private function queryGetTrees()
    {
        // get the parent class of the model
        $refClass = new \ReflectionClass($this->model);
        $parentClass = $refClass->getParentClass()->name;

        $query = Tag::find()
            ->joinWith('tagModelsAuthItems')
            ->andWhere([
            'classname' => [get_class($this->model), $parentClass],
            'auth_item' => $this->getUserRoles()
        ]);

        return $query;
    }

    /**
     * Method to get the list of role names for the authenticated user
     * roles of the authenticated user
     *
     * @return array | $roles
     */
    private function getUserRoles()
    {
        // get the list of role names for the authenticated user
        $roles_key = array_keys(
            \Yii::$app
                ->getAuthManager()
                ->getRolesByUser(\Yii::$app->user->id)
        );

        // merge the list of role names with the names of the child roles
        $roles = [];
        foreach ($roles_key as $key => $role) {
            $role_children = array_keys(
                \Yii::$app->getAuthManager()->getChildRoles($role)
            );
            $roles = array_merge($roles, $role_children);
        }

        $roles = array_unique($roles);

        return $roles;
    }

    /**
     * Method to get list of all trees tags
     *
     * @return array | model | open20\amos\tag\models\Tag
     */
    private function getTreesTags()
    {
        return $this->queryGetTreeTags(null)
            ->andWhere(['deleted_at' => null])
            ->orderBy([
                'root' => SORT_ASC,
                'nome' => SORT_ASC
            ])
            ->all();
    }

    /**
     * Method to get the query for extracting (all, owner_interest)
     * or specific (id / root) Trees Node Tags
     * from table tag or / and where auth user->id in cwh_tag_owner_interest_mm
     *
     * @return query
     */
    private function queryGetTreeTags()
    {
        // get the parent class of the model
        $refClass = new \ReflectionClass($this->model);
        $parentClass = $refClass->getParentClass()->name;

        if ($this->filter == "owner-interest") {
            // get query to extract owner interest tags associated
            // with the auth user
            $sub_query = CwhTagOwnerInterestMm::find()
                ->select('tag_id as id')
                ->andWhere(['classname' => 'common\models\UserProfile']) // ?? classname -> common\models\UserProfile
                ->andWhere(['record_id' => \Yii::$app->user->id])
                ->andWhere(['deleted_at' => null]);

            $query = Tag::find()
                ->andWhere(['>', 'lvl', 0])
                ->andWhere(['id' => $sub_query]);
            
        } elseif ($this->filter == "own-content-created") {
            $sub_query = (new \yii\db\Query())
                ->select('tag_id as id')
                ->from('entitys_tags_mm')
                ->andWhere([
                    'classname' => [get_class($this->model), $parentClass],
                ])
                ->andWhere(['created_by' => \Yii::$app->user->id])
                ->andWhere(['deleted_at' => null]);

            $query = Tag::find()
                ->andWhere(['>', 'lvl', 0])
                ->andWhere(['id' => $sub_query]);

        } elseif ($this->filter == "all-in-content") {
            $sub_query = (new \yii\db\Query())
                ->select('tag_id as id')
                ->from('entitys_tags_mm')
                ->andWhere([
                    'classname' => [get_class($this->model), $parentClass],
                ])
                ->andWhere(['deleted_at' => null]);

            $query = Tag::find()
                ->andWhere(['>', 'lvl', 0])
                ->andWhere(['id' => $sub_query]);
        } else {
            // get query to extract all tags
            $query = Tag::find()
                ->andWhere(['root' => ArrayHelper::getColumn($this->trees, 'id')])
                ->andWhere(['>', 'lvl', 0])
                ->addOrderBy('root, lft');
        }

        return $query;
    }

    /**
     * Method to get auth User Tags
     *
     * @return array | $data
     */
    private function getRootAndId()
    {
        $tags = $this->queryGetTreeTags()->all();

        $data = ArrayHelper::map(
            $tags,
            function ($tag) {
                return $tag->root . '-' . $tag->id;
            },
            'father', 'rootName'
        );

        return $data;
    }

}