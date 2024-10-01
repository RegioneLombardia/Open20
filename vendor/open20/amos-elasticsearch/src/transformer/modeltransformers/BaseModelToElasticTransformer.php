<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch\transformer\modeltransformers;

use open20\amos\core\record\CachedActiveQuery;
use open20\amos\core\record\ContentModel;
use open20\amos\core\utilities\ClassUtility;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use open20\elasticsearch\transformer\AbstractModelToElasticTransformer;
use open20\elasticsearch\transformer\AbstractTransformer;
use yii\db\ActiveRecord;

class BaseModelToElasticTransformer extends AbstractModelToElasticTransformer {

    const MIN_DATE = '0000-00-00 00:00:00';
    const MAX_DATE = '9999-12-31 23:59:59';

    public $tagValuesSeparatorAttribute = "','";
    protected $model;

    public function setModel($model) {
        $this->model = $model;
        return $this;
    }

    public function getModel() {
        return $this->model;
    }

    public function getTags() {
        $string_tags = '';

        $tags = $this->model->getTagValues(true);
        if(!count($tags)){
            $tags[] = AbstractTransformer::NOTAGS_TAG ;
        }
        $string_tags = sprintf("'%s'", implode($this->tagValuesSeparatorAttribute, $tags));
        return $string_tags;
    }

    /**
     * 
     * @return ActiveRecord
     */
    public function getCwhPubblicazione() {
        $pubblicazione = null;
        if ($this->model && !$this->model->isNewRecord) {
            $cwhConfigContentsQuery = CwhConfigContents::find()->andWhere(['tablename' => $this->model->tableName()]);
            $cwhConfigContentsQuery = CachedActiveQuery::instance($cwhConfigContentsQuery);
            $cwhConfigContentsQuery->cache(60);
            $cwhConfigContents = $cwhConfigContentsQuery->one();

            /**
             * @var CwhPubblicazioni $Pubblicazione ;
             */
            $pubblicazioneQuery = CwhPubblicazioni::find()
                    ->andWhere(['content_id' => $this->model->id])
                    ->andWhere(['cwh_config_contents_id' => $cwhConfigContents->id]);
            $pubblicazioneQuery = CachedActiveQuery::instance($pubblicazioneQuery);
            $pubblicazioneQuery->cache(60);
            $pubblicazione = $pubblicazioneQuery->one();
        }
        return $pubblicazione;
    }

    /**
     * 
     * @param ActiveRecord $model
     * @param array $values
     * @return array
     */
    public function cwhEvaluation($values) {
        $ret = $values;

        $cwhmodule = AmosCwh::instance();
        if (!is_null($cwhmodule)) {
            if (in_array(get_class($this->model), $cwhmodule->modelsEnabled)) {
                $pubblicazione = $this->getCwhPubblicazione();
                if (!is_null($pubblicazione)) {
                    switch ($pubblicazione->cwh_regole_pubblicazione_id) {
                        case CwhRegolePubblicazione::ALL_USERS_WITH_TAGS:
                            $ret[AbstractTransformer::TAGS_INDEX] = $this->getTags();
                        case CwhRegolePubblicazione::ALL_USERS :
                            $ret[AbstractTransformer::NETWORKS_INDEX] = AbstractTransformer::PLATFORM_TAG . " ";
                            break;
                        case CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS_WITH_TAGS:
                            $ret[AbstractTransformer::TAGS_INDEX] = $this->getTags();
                        case CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS:
                            $networks = "";
                            foreach ($pubblicazione->destinatari as $comm) {
                                $networkClass = $comm->classname;
                                $network_tag = strtolower(ClassUtility::getClassBasename($comm->classname)) . $comm->record_id;
                                $networks .= $network_tag . " ";
                            }
                            $ret[AbstractTransformer::NETWORKS_INDEX] = $networks;
                            break;
                    }
                    $ret = $this->statusEvaluation($ret);
                }
            } else {
                $ret[AbstractTransformer::NETWORKS_INDEX] = AbstractTransformer::PLATFORM_TAG . " ";
                $ret[AbstractTransformer::TAGS_INDEX] = sprintf("'%s'",AbstractTransformer::NOTAGS_TAG);
            }
        }
        return $ret;
    }

    public function statusEvaluation($values){
        $ret = $values;
        if ($this->model instanceof ContentModel){
            switch($this->model->status){
                case $this->model->getDraftStatus():
                    $ret[AbstractTransformer::STATUS_INDEX] = AbstractTransformer::DRAFT_VALUE; 
                    break;
                case $this->model->getToValidateStatus():
                    $ret[AbstractTransformer::STATUS_INDEX] = AbstractTransformer::TO_VALIDATE_VALUE; 
                    break;
                case $this->model->getValidatedStatus():
                    $ret[AbstractTransformer::STATUS_INDEX] = AbstractTransformer::VALIDATED_VALUE; 
                    break;
            }
        }
        return $ret;
    }
}
