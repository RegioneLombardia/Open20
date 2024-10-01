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
use open20\amos\cwh\models\CwhTagInterestMm;
use open20\amos\cwh\models\Topic;
use open20\amos\tag\models\Tag;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

class CardTagWidgetAreeInteresse extends InputWidget {

    public $form;
    public $contentsTreesSimple = [];
    public $content;
    public $baseIconsUrl = '/sprite/material-sprite.svg#';
    public $showTagLabel = false;
    public $customCategoriesLabel = [];
    public $showTitle = false;

    public function init() {
        parent::init();
        $this->content = AmosAdmin::instance()->model('UserProfile');
        $this->fetchContentsTrees();
    }

    private function fetchContentsTrees() {

        $moduleTag = Yii::$app->getModule('tag');
        if (isset($moduleTag)) {

            $id_user = $this->model['user_id'];

            //query di recupero dei tags
            /** @var ActiveQuery $query */
            $query = Tag::find()
                ->joinWith('cwhTagInterestMm')
                ->andWhere([
                    CwhTagInterestMm::tableName() . '.classname' => $this->content,
                    CwhTagInterestMm::tableName() . '.auth_item' => array_keys(Yii::$app->authManager->getRolesByUser($id_user))
                ]);

            if ($query->count()) {
                $this->contentsTreesSimple = $this->contentsTreesSimple + ArrayHelper::map($query->all(), 'id', 'nome');
            }
        }
    }

    public function run() {
        $html = $this->showTitle ? "<h4>Scegli gli argomenti di tuo interesse:</h4>" : '';
        $selected = $this->getTagsSelected();
        foreach ($this->contentsTreesSimple as $key => $tagName) {
            $label = '';
            if($this->showTagLabel) {
                if (isset($this->customCategoriesLabel[$tagName])) {
                    $label = $this->customCategoriesLabel[$tagName];
                } else {
                    $label = $tagName;
                }
            }
            $html .= $this->form->field($this->model, $this->attribute)->widget(CheckBoxListTopicsIcon::className(), [
                'choices' => $this->getTagsTopicArray($key),
                'classContainer' => 'col-lg-4 col-sm-6 aria-themetag',
                'baseIconsUrl' => $this->baseIconsUrl,
                'selected' => $selected,
                'rootId' => $key,
            ])->label($label);
        }
        return $html;
    }

    /**
     * Example of return:<br />
     * [<br />
     *     code-topic => label,<br />
     *     0 => Topic { id -> 1, label -> 'Innovazione' ...},<br />
     *     1 => Topic { id -> 2 , label -> 'Ambiente e Sviluppo sostenibile' ...},<br />
     *     ...<br />
     * ]<br />
     * <br />
     * @return array
     */
    public function getTagsTopicArray($idRoot) {
        $toret = [];
        $tags = $this->getTagQuery($idRoot)->all();
        foreach ($tags as $tag) {
            if (!empty($tag)) {
                $topic = new Topic();
                $topic->id = $tag->id;
                $topic->label = $tag->nome;
                $topic->description = $tag->descrizione;
                $topic->icon = $tag->icon;
                $toret[] = $topic;
            }
        }
        return $toret;
    }

    /**
     *
     * @param type $idRoot
     * @return ActiveQuery
     */
    public function getTagQuery($idRoot) {
        //query di recupero dei tags
        /** @var ActiveQuery $query */
        $query = Tag::find();
        $query->andWhere(["tag.root" => $idRoot]);
        $query->andWhere(['!=', "tag.id", $idRoot]);
        $query->orderBy('id');
        return $query;
    }

    /**
     * i tag selezionati per il record in esame
     * @return array
     */
    private function getTagsSelected() {

        //data la tabella delle mm tra record e oggetti, recupera le row
        //dell'oggetto per il model in esame
        $listaTagId = \open20\amos\cwh\models\CwhTagOwnerInterestMm::findAll([
            'classname' => $this->content,
            'record_id' => $this->model->id
        ]);

        $ret = [];
        foreach ($listaTagId as $tag) {
            //recupera il tag
            $tagObj = $this->getTagById($tag->tag_id);
            if (is_null($tagObj)) {
                continue;
            }

            //identifica l'id dell'albero
            $id_tree = $tagObj->root;
            $name = $tag->interest_classname;
            //aggiunge il tag nell'elenco dell'albero relativo
            $ret[$id_tree][] = $tagObj->id;
        }

        return $ret;
    }

    /**
     * @param $tagId
     * @return \open20\amos\tag\models\Tag
     */
    private function getTagById($tagId) {
        return \open20\amos\tag\models\Tag::findOne($tagId);
    }

}
