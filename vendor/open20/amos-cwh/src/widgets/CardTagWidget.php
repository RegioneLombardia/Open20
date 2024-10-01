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

class CardTagWidget extends InputWidget {

    public $form;
    public $contentsTreesSimple = [];
    public $content;
    public $baseIconsUrl = '/sprite/material-sprite.svg#';
    public $codiceRoot = [];
    public $showTagLabel;
    public $selected = [];

    public function init() {
        parent::init();
        $this->content = AmosAdmin::instance()->model('UserProfile');
        $this->fetchContentsTrees();
    }

    private function fetchContentsTrees() {

        $moduleTag = Yii::$app->getModule('tag');
        if (isset($moduleTag)) {
            
            //query di recupero dei tags
            /** @var ActiveQuery $query */
            $query = Tag::find()
                    ->andWhere([
                Tag::tableName() . '.lvl' => 0,
                
            ]);

            if (!empty($this->codiceRoot)) {
                $rootList = [];
                foreach ($this->codiceRoot as $codice) {
                    $tag = Tag::findOne(['codice' => $codice]);
                    if (!empty($tag->root)) {
                        $rootList[] = $tag->root;
                    }
                }
                $query->andWhere(['in', Tag::tableName() . '.root', $rootList]);
            }

            if ($query->count()) {
                $this->contentsTreesSimple = $this->contentsTreesSimple + ArrayHelper::map($query->all(), 'id', 'nome');
            }
        }
    }

    public function run() {
        $html = "";
        $selected = [];

        foreach ($this->contentsTreesSimple as $key => $tagName) {
            $tags = $this->getTagsTopicArray($key);
            if(!empty($tags)) {
                $html .= $this->form->field($this->model, $this->attribute)->widget(CheckBoxListTopicsIcon::className(), [
                    'choices' => $tags,
                    'classContainer' => 'col-lg-4 col-sm-6 aria-themetag',
                    'baseIconsUrl' => $this->baseIconsUrl,
                    'selected' => $this->selected,
                    'rootId' => $key,
                ])->label($this->showTagLabel ? $tagName : '');
            }
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
        $tags = $this->getTagQuery($idRoot)->orderBy('nome')->all();
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
