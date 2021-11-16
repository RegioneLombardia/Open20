<?php

namespace open20\amos\sondaggi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use open20\amos\sondaggi\models\SondaggiInvitations;
use open20\amos\sondaggi\models\SondaggiInvitationMm;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\tag\models\Tag;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloGroups;
use open20\amos\organizzazioni\models\ProfiloTypesPmi;

/**
 * SondaggiDomandeSearch represents the model behind the search form about `open20\amos\sondaggi\models\SondaggiDomande`.
 */
class SondaggiInvitationsSearch extends SondaggiInvitations {

    public function rules() {
        return SondaggiInvitations::rules();
    }

    public function scenarios() {
// bypass scenarios() implementation in the parent class
        return SondaggiInvitations::scenarios();
    }

    public static function getListOfAttributes()
    {
        return [
            'type' => AmosSondaggi::t('AmosSondaggi', 'Tipologia'),
            'name' => AmosSondaggi::t('AmosSondaggi', "Denominazione")
        ];
    }

    public static function getAttributesValues($type, $query) {
        $data = [];
        if ($type == 'type') {
            $data = ProfiloTypesPmi::find()->select(['id', 'name'])->andFilterWhere(['like', 'name', $query])->all();
        }
        if ($type == 'name') {
            $data = Profilo::find()->select(['id', 'name'])->andFilterWhere(['like', 'name', $query])->all();
        }
        return $data;
    }

    public static function getPollTags($query) {
        return Tag::find();
    }

    public static function getPollGroups($query) {
        return ProfiloGroups::find();
    }

    public function search($params) {
        $query = SondaggiInvitations::find();
        if(isset($params['idSondaggio'])){
            $query->andWhere(['sondaggi_id' => $params['idSondaggio']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // if (!($this->load($params) && $this->validate())) {
        //     return $dataProvider;
        // }

        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'sondaggi_id' => $this->sondaggi_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        return $dataProvider;
    }

    public static function searchOrganizations($params) {
        $query = Profilo::find();
        if ($params['type'] == SondaggiInvitations::SEARCH_FILTER) {
            if ($params['filter_type'] == SondaggiInvitations::FILTER_GROUPS) {
                $query = $query->joinWith('profiloGroups')->andFilterWhere(['profilo_groups.id' => $params['search_groups']]);
            }
            if ($params['filter_type'] == SondaggiInvitations::FILTER_INVITED_TAG) {
                $tags = $params['search_tags'];
                $tagged = EntitysTagsMm::find()
                    ->select('record_id')
                    ->andWhere(['classname' => Sondaggi::className()])
                    ->andWhere(['tag_id' => $tags])->all();

                // Converting result to a list of poll IDs, and removing current poll to avoid loops...
                $polls = ArrayHelper::getColumn($tagged, 'record_id');
                if (($key = array_search($params['sondaggi_id'], $polls)) !== false) {
                    unset($polls[$key]);
                }

                $invitations = SondaggiInvitationMm::find()->select('to_id')->distinct()->andWhere(['sondaggi_id' => $polls])->all();
                $invited = ArrayHelper::getColumn($invitations, 'to_id');
                $query = $query->andWhere(['id' => $invited]);
            }
            if ($params['filter_type'] == SondaggiInvitations::FILTER_COMPILED_TAG) {
                $tags = $params['search_tags'];
                $tagged = EntitysTagsMm::find()
                    ->select('record_id')
                    ->andWhere(['classname' => Sondaggi::className()])
                    ->andWhere(['tag_id' => $tags])->all();

                // Converting result to a list of poll IDs, and removing current poll to avoid loops...
                $polls = ArrayHelper::getColumn($tagged, 'record_id');
                if (($key = array_search($params['sondaggi_id'], $polls)) !== false) {
                    unset($polls[$key]);
                }

                $answers = SondaggiRisposteSessioni::find()
                    ->select('organization_id')->distinct()
                    ->andWhere(['sondaggi_id' => $polls])
                    ->andWhere(['status' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO]);
                \Yii::debug($answers->createCommand()->rawSql, 'sondaggi');
                $answers = $answers->all();
                $answeredOrg = ArrayHelper::getColumn($answers, 'organization_id');
                $query = $query->andWhere(['id' => $answeredOrg]);
            }
        }
        for ($i = 1; $i <= count($params['field']); $i++) {
            $operator = '=';
            if ($params['include_exclude'][$i] == SondaggiInvitations::FILTER_EXCLUDE) $operator = '!=';
            if ($params['field'][$i] == 'type') {
                $query = $query->joinWith('tipologiaDiOrganizzazione')->andFilterWhere([$operator, 'profilo_types_pmi.id', $params['value'][$i]]);
            }
            if ($params['field'][$i] == 'name') {
                $query = $query->andFilterWhere([$operator, 'profilo.id', $params['value'][$i]]);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
