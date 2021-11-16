<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag\widgets
 * @category   CategoryName
 */

namespace open20\amos\tag\widgets;

use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\widgets\InputWidget;

/**
 * Class TagWidget
 *
 * @property \open20\amos\core\record\Record $model
 *
 * @package open20\amos\tag\widgets
 */
class TagWidget extends InputWidget
{

    public
        $id = 'amos-tag', // @var string $id the id of the widget container div
        $containerClass = 'tag-widget', // @var string $containerClass the class of the widget container div

        $form,
        $name = 'tagValues',
        $trees = [],
        $singleFixedTreeId,
        $form_values,
        $isSearch = false,
        $hideHeader = false,
        $moduleCwh,
        $scope,
        $selectSonsOnly;
    private static
        $allRoles;

    /**
     * @throws \ReflectionException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!isset($this->form_values)) {
            $post = Yii::$app->request->post($this->model->formName());
            if (!empty($post) && key_exists($this->name, $post)) {
                $this->form_values = $post[$this->name];
            }
        }

        $this->trees = $this->fetchTrees();

        if (is_null($this->selectSonsOnly)) {
            $tags = \Yii::$app->getModule(\open20\amos\tag\AmosTag::getModuleName());

            if ($tags) {
                $this->selectSonsOnly = $tags->selectSonsOnly;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $tagsSelected = $this->form_values ? $this->getTagsSelectedFromFormValues() : $this->getTagsSelected();

        return $this->render(
                'tag',
                [
                    'model' => $this->model,
                    'form' => $this->form,
                    'name' => $this->name,
                    'trees' => $this->trees,
                    'is_search' => $this->isSearch,
                    'tags_selected' => $tagsSelected,
                    'limit_trees' => $this->getLimitTrees(),
                    'hideHeader' => $this->hideHeader,
                    'id' => $this->id,
                    'containerClass' => $this->containerClass,
                    'moduleCwh' => $this->moduleCwh,
                    'scope' => $this->scope,
                    'selectSonsOnly' => $this->selectSonsOnly
                ]
        );
    }

    /**
     * @return array|ActiveRecord[]
     * @throws \ReflectionException
     */
    private function fetchTrees()
    {
        $refClass = new \ReflectionClass($this->model);
        $parentClass = $refClass->getParentClass()->name;

        /** @var ActiveQuery $query */
        $query = Tag::find()
            ->joinWith('tagModelsAuthItems')
            ->andWhere([
            'classname' => [get_class($this->model), $parentClass],
            'auth_item' => $this->getAllRoles()
        ]);

        if (!empty($this->singleFixedTreeId)) {
            $query->andWhere(["tag.id" => $this->singleFixedTreeId]);
        }

        return $query
                ->orderBy(['tag.nome' => SORT_DESC])
                ->all();
    }

    /**
     * @return array the roles that the user logged have
     */
    private function getAllRoles()
    {
        if (is_null(static::$allRoles)) {
            if (\open20\amos\admin\AmosAdmin::instance()->modelMap['UserProfile'] == get_class($this->model)) {
                $id = $this->model['user_id'];
            } else {
                $id = \Yii::$app->getUser()->getId();
            }

            $keysRoles = array_keys(
                \Yii::$app
                    ->getAuthManager()
                    ->getRolesByUser($id)
            );

            // i want all roles that user has
            static::$allRoles = [];
            foreach ($keysRoles as $role) {
                static::$allRoles = array_unique(
                    array_merge(
                        static::$allRoles,
                        array_keys(\Yii::$app->getAuthManager()->getChildRoles($role))));
            }
        }

        return static::$allRoles;
    }

    /**
     * Data la tabella delle mm tra record e oggetti, recupera le row
     * dell'oggetto per il model in esame
     * 
     * Returns the selected tags for the passed record.
     * @return array
     */
    private function getTagsSelected()
    {
        $listaTagId = EntitysTagsMm::findAll([
                'classname' => get_class($this->model),
                'record_id' => $this->model->id,
        ]);

        $ret = [];
        foreach ($listaTagId as $tag) {
            //recupera il tag
            $tagObj = $this->getTagById($tag->tag_id);

            if (!empty($tagObj)) {
                //identifica l'id dell'albero
                $id_tree = $tagObj->root;

                //verifica se esiste già il riferimento per l'albero in esame
                //e nel caso la crea
                if (!array_key_exists("tree_" . $id_tree, $ret)) {
                    $ret["tree_" . $id_tree] = [];
                }

                //aggiunge il tag nell'elenco dell'albero relativo
                $ret["tree_" . $id_tree][] = [
                    "id" => $tagObj->id,
                    "label" => $tagObj->nome
                ];
            }
        }

        return $ret;
    }

    /**
     * @return array
     */
    private function getTagsSelectedFromFormValues()
    {
        $ret = [];

        if (isset($this->form_values)) {
            foreach ($this->form_values as $treeId => $treeSelectedTags) {
                $selectedTagIds = explode(',', $treeSelectedTags);
                if ($treeSelectedTags && !array_key_exists("tree_" . $treeId, $ret)) {
                    $ret["tree_" . $treeId] = [];
                }

                foreach ($selectedTagIds as $tagId) {
                    if (!empty($tagId)) {
                        $tagObj = $this->getTagById($tagId);
                        if (!is_null($tagObj)) {
                            if ($treeId == $tagObj->root) {
                                if (!array_key_exists("tree_" . $treeId, $ret)) {
                                    $ret["tree_" . $treeId] = [];
                                }

                                $element = [
                                    "id" => $tagObj->id,
                                    "label" => $tagObj->nome
                                ];

                                if (!in_array($element, $ret["tree_" . $treeId])) {
                                    $ret["tree_" . $treeId][] = $element;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * @param int $tagId
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

        foreach ($this->trees as $tree) {
            //limite di default: nessun limite
            $limit_tree = false;

            if (!$this->isSearch) {
                //carica il nodo radice
                $root_node = $this->getTagById($tree['id']);

                //se è presente un limite impostato per questa radice allora lo usa
                if ($root_node->limit_selected_tag && is_numeric($root_node->limit_selected_tag)) {
                    $limit_tree = $root_node->limit_selected_tag;
                }
            }

            $array_limit_trees["tree_" . $tree['id']] = $limit_tree;
        }

        return $array_limit_trees;
    }

    /**
     * @return ActiveRecord[] tutte le root
     */
    private function fetchRoles()
    {
        /*         * @var ActiveQuery $query * */

        return Tag::find()
                ->joinWith('tagAuthItemsMms')
                ->andWhere(['auth_item' => array_keys(\Yii::$app->authManager->getRolesByUser(\Yii::$app->getUser()->getId()))])
                ->all();
    }

}
