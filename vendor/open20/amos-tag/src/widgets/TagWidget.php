<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag\widgets
 * @category   CategoryName
 */

namespace open20\amos\tag\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
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
    /**
     * @var AmosTag $tagModule
     */
    public $tagModule = null;

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
        $selectSonsOnly,
        $isFrontend = false,
        $enableAjax = false,
        $responseAjax = false,
        $treeOptions = [];
    private static
        $allRoles;

    /**
     * @throws \ReflectionException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->tagModule = AmosTag::instance();

        parent::init();
        if (!isset($this->form_values)) {
            $post = Yii::$app->request->post($this->model->formName());
            if (!empty($post) && key_exists($this->name, $post)) {
                $this->form_values = $post[$this->name];
            }
        }
        $this->trees = $this->fetchTrees();
        if (is_null($this->selectSonsOnly)) {
            $tags = \Yii::$app->getModule(AmosTag::getModuleName());
            if ($tags) {
                $this->selectSonsOnly = $tags->selectSonsOnly;
            }
        }
    }

    public function registerAjax($uniqueId)
    {

        $this->view->registerJsVar("classnameContent_$uniqueId", get_class($this->model));
        $this->view->registerJsVar("idContent_$uniqueId", $this->model->isNewRecord ? '' : $this->model->id);
        $this->view->registerJsVar("isSearch_$uniqueId", $this->isSearch);
        $this->view->registerJsVar("hideHeader_$uniqueId", $this->hideHeader);
        $this->view->registerJsVar("selectSonsOnly_$uniqueId", $this->selectSonsOnly);
        $this->view->registerJsVar("name_$uniqueId", $this->name);
        $this->view->registerJsVar("containerClass_$uniqueId", $this->containerClass);
        $errorBlockMessage = AmosTag::t('amostag', 'Selezionare almeno 1 tag.');
        $errorTooltipTitle = AmosTag::t('amostag', 'E\' necessario scegliere almeno 1 tag');
        $this->view->registerJsVar("optionsTrees_$uniqueId", \yii\helpers\Json::htmlEncode($this->treeOptions));
        $this->view->registerJsVar("previousPost_$uniqueId", serialize(\Yii::$app->request->get()));
        $js = <<<JS
        var errorBlockMessage = "$errorBlockMessage";
        var errorTooltipTitle = "$errorTooltipTitle";
        $.ajax(
            {
        url: '/tag/tag-ajax/tag-widget',
        type: 'get',
        data: {
            attribute: '$this->attribute',
            classname: classnameContent_$uniqueId,
            record_id: idContent_$uniqueId,
            previousPost :previousPost_$uniqueId,
            isSearch: isSearch_$uniqueId,
            hideHeader: hideHeader_$uniqueId,
            selectSonsOnly: selectSonsOnly_$uniqueId,
            containerClass: containerClass_$uniqueId,
            name: name_$uniqueId,
        },
        success: function (data) {
                $('#$uniqueId').html(data);
                $("#cwh-regola_pubblicazione").trigger('change');

                // This will collapse all tag trees when page is ready
                 $('input[id^="tree_obj_"]').treeview("collapseAll");
            
                // Show an error message at the bottom of the section title if in the page there is a "*-regola_pubblicazione"
                // class with an error in it.
                if($('div[class*="-regola_pubblicazione"]').hasClass('has-error')){
                    $(".tag-plugin-block-error").append('<div style="margin-bottom: 10px;"><span class="tooltip-error-field"><span class="help-block help-block-error">'+errorBlockMessage+'</span></span></div>');
                    $(".tag-plugin-warning-triangle").append('<span class="tooltip-error-field"> <span title="" data-toggle="tooltip" data-placement="top" data-original-title="'+errorTooltipTitle+'"><span class="am am-alert-triangle" style="color: #a02622"> </span> </span> </span>');
                    $('div[class*="-regola_pubblicazione"]').hide();
                }
                if($('#amos-tag div').hasClass('has-error')){
                    $(".tag-plugin-block-error").append('<div style="margin-bottom: 10px;"><span class="tooltip-error-field"><span class="help-block help-block-error">'+errorBlockMessage+'</span></span></div>');
            
                }
                
               if (typeof optionsTrees_$uniqueId != 'undefined') {
                    var options = JSON.parse(optionsTrees_$uniqueId);
                        options.forEach(function (TagFormVar, indexTree) {
                            renderPreview(TagFormVar.data_trees.tags_selected, TagFormVar.data_trees.id, TagFormVar.data_trees.limit);
                            onlyLeavesSelectable(TagFormVar.selectSonsOnly);
                        });
                }

           // renderTagTree();
            }
        });
JS;
        $this->view->registerJs($js);

    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $html = '';
        $tagsSelected = $this->form_values ? $this->getTagsSelectedFromFormValues() : $this->getTagsSelected();
        $hideHeaderInternal = (isset(\Yii::$app->params['hideTagWidgetHeader']) ? \Yii::$app->params['hideTagWidgetHeader'] : $this->hideHeader);
        $this->hideHeader = $hideHeaderInternal;

        if ($this->enableAjax) {
            $html.= $this->ajaxCall($tagsSelected);
        }

        if(!$this->enableAjax || ($this->responseAjax && $this->enableAjax)) {
            $html = $this->render(
                'tag',
                [
                    'model' => $this->model,
                    'form' => $this->form,
                    'name' => $this->name,
                    'trees' => $this->trees,
                    'is_search' => $this->isSearch,
                    'tags_selected' => $tagsSelected,
                    'limit_trees' => $this->getLimitTrees(),
                    'hideHeader' => $hideHeaderInternal,
                    'id' => $this->id,
                    'containerClass' => $this->containerClass,
                    'moduleCwh' => $this->moduleCwh,
                    'scope' => $this->scope,
                    'selectSonsOnly' => $this->selectSonsOnly,
                    'isFrontend' => $this->isFrontend,
                    'enabledAjax' => $this->enableAjax
                ]
            );
        }
        return $html;

    }

    public function ajaxCall($tagsSelected)
    {
        if ($this->responseAjax) {
            $bundle = [];
            foreach (\Yii::$app->controller->view->assetBundles as $name => $asset) {
                if ($name != 'yii\bootstrap\BootstrapAsset') {
                    $bundle[$name] = $asset;
                }
            }
            \Yii::$app->controller->view->assetBundles = $bundle;
        } else {
            $uniqueId = uniqid();
            \open20\amos\tag\assets\ModuleTagFormAjaxAsset::register($this->view);
            \open20\amos\layout\assets\ThreeDotsAsset::register($this->view);
            $js = $this->getTreeJs($this->getLimitTrees(), $tagsSelected);
            $this->registerAjax($uniqueId);
            return $js . "
                <div id='$uniqueId' class='tag-section-ajax'>
                    <div class='col-md-12'>
                        <div class='loader-3dots'>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>";
        }
        return '';
    }

    /**
     * @param $limit_trees
     * @param $tags_selected
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getTreeJs($limit_trees, $tags_selected)
    {
        $data_trees = [];
        $render = '';
        foreach ($this->trees as $tree) {
            //dati dell'albero
            $data_tree = \open20\amos\tag\widgets\TagWidget::getDataTree($tree, $limit_trees, $tags_selected);

            $options = [
                'data_trees' => $data_tree,
                'selectSonsOnly' => $this->selectSonsOnly,
                'error_limit_tags' => AmosTag::t('amostag', 'Hai superato le scelte disponibili per questi descrittori.'),
                'tags_unlimited' => AmosTag::t('amostag', 'illimitate'),
                'no_tags_selected' => AmosTag::t('amostag', 'Nessun tag selezionato'),
                'icon_remove_tag' => \open20\amos\core\icons\AmosIcons::show('close', [], 'am'),
            ];
            $data_trees[] = $options;
//            pr($options);
            $render .= $this->render('_tag_js', ['options' => $options]);

        }
        $this->treeOptions = $data_trees;
        return $render;
    }


    /**
     * @return array|ActiveRecord[]
     * @throws \ReflectionException
     */
    private function fetchTrees()
    {
        $refClass    = new \ReflectionClass($this->model);
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
            if (
                Yii::$app->getModule(
                    AmosAdmin::getModuleName()
                )
                ->modelMap['UserProfile'] == get_class($this->model)
            ) {
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
                        array_keys(
                            \Yii::$app->getAuthManager()->getChildRoles($role))
                    )
                );
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
        $ret = [];

        if ($this->model->isNewRecord) {
            $attributeName = $this->name;
            $tagValues     = $this->model->{$attributeName};

            if (empty($tagValues)) {
                return $ret;
            } else {
                /** @var Tag $tagModel */
                $tagModel          = $this->tagModule->createModel('Tag');
                $tagTable          = $tagModel::tableName();
                $tagsToSearchArray = explode(',', trim(str_replace(' ', '', $tagValues)));

                $query = new Query();
                $query
                    ->select([$tagTable.'.id', $tagTable.'.nome', $tagTable.'.root'])
                    ->from($tagTable)
                    ->andWhere([$tagTable.'.deleted_at' => null])
                    ->andWhere([$tagTable.'.id' => $tagsToSearchArray]);

                $tags = $query->all();
            }
        } else {
            /** @var Tag $tagModel */
            $tagModel = $this->tagModule->createModel('Tag');
            $tagTable = $tagModel::tableName();

            /** @var EntitysTagsMm $entitysTagsMmModel */
            $entitysTagsMmModel = $this->tagModule->createModel('EntitysTagsMm');
            $entitysTagsMmTable = $entitysTagsMmModel::tableName();

            $query = new Query();
            $query->select([$tagTable.'.id', $tagTable.'.nome', $tagTable.'.root'])
                ->from($entitysTagsMmTable)
                ->innerJoin($tagTable, $tagTable.'.id = '.$entitysTagsMmTable.'.tag_id')
                ->andWhere([$tagTable.'.deleted_at' => null])
                ->andWhere([$entitysTagsMmTable.'.deleted_at' => null])
                ->andWhere([$entitysTagsMmTable.'.classname' => get_class($this->model)])
                ->andWhere([$entitysTagsMmTable.'.record_id' => $this->model->id]);

            $tags = $query->all();
        }

        foreach ($tags as $tag) {
            // Identifica l'id dell'albero
            $id_tree = $tag['root'];

            // Verifica se esiste già il riferimento per l'albero in esame
            // e nel caso la crea
            if (!array_key_exists('tree_'.$id_tree, $ret)) {
                $ret['tree_'.$id_tree] = [];
            }

            // Aggiunge il tag nell'elenco dell'albero relativo
            $ret['tree_'.$id_tree][] = [
                'id' => $tag['id'],
                'label' => $tag['nome']
            ];
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
                if (
                    $treeSelectedTags && !array_key_exists('tree_'.$treeId, $ret)
                ) {
                    $ret['tree_'.$treeId] = [];
                }

                foreach ($selectedTagIds as $tagId) {
                    if (!empty($tagId)) {
                        $tagObj = $this->getTagById($tagId);
                        if (!is_null($tagObj)) {
                            if ($treeId == $tagObj->root) {
                                if (!array_key_exists("tree_".$treeId, $ret)) {
                                    $ret['tree_'.$treeId] = [];
                                }

                                $element = [
                                    'id' => $tagObj->id,
                                    'label' => $tagObj->nome
                                ];

                                if (!in_array($element, $ret['tree_'.$treeId])) {
                                    $ret['tree_'.$treeId][] = $element;
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
                if (
                    $root_node->limit_selected_tag && is_numeric($root_node->limit_selected_tag)
                ) {
                    $limit_tree = $root_node->limit_selected_tag;
                }
            }

            $array_limit_trees['tree_'.$tree['id']] = $limit_tree;
        }

        return $array_limit_trees;
    }

    /**
     * @return ActiveRecord[] tutte le root
     */
    private function fetchRoles()
    {
        $roles = array_keys(
            \Yii::$app->authManager->getRolesByUser(
                \Yii::$app->getUser()->getId()
            )
        );

        return Tag::find()
                ->joinWith('tagAuthItemsMms')
                ->andWhere(['auth_item' => $roles])
                ->all();
    }

    /**
     * @param $trees
     * @param $limit_trees
     * @param $tags_selected
     * @return array
     */
    public static function getDataTree($tree, $limit_trees, $tags_selected)
    {

        //dati dell'albero
        $id_tree = $tree['id'];
        $label_tree = $tree['nome'];
        $limit_tree = ((array_key_exists('tree_' . $id_tree, $limit_trees) && $limit_trees['tree_' . $id_tree])
            ? $limit_trees['tree_' . $id_tree]
            : false
        );

        $tags_selected_tree = [];
        if (array_key_exists('tree_' . $id_tree, $tags_selected) && !empty($tags_selected["tree_" . $id_tree])) {
            $tags_selected_tree = $tags_selected["tree_" . $id_tree];
        }

        //inserisce i dati nell'array per gli eventi js
        $data_tree = [
            'id' => $id_tree,
            'limit' => $limit_tree,
            'label' => $label_tree,
            'tags_selected' => $tags_selected_tree
        ];
        return $data_tree;
    }

}
