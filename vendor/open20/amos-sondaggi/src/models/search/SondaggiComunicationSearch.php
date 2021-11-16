<?php

namespace open20\amos\sondaggi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\sondaggi\models\SondaggiComunication;

/**
 * SondaggiComunicationSearch represents the model behind the search form about `open20\amos\sondaggi\models\SondaggiComunication`.
 */
class SondaggiComunicationSearch extends SondaggiComunication
{

    public function rules()
    {
        return [
            [['id', 'sondaggi_id', 'count', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'subject', 'message', 'query', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getScope($params)
    {
        $scope = $this->formName();
        if (!isset($params[$scope])) {
            $scope = '';
        }
        return $scope;
    }

    public function search($params)
    {
        $query = SondaggiComunication::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $scope = $this->getScope($params);
        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'sondaggi_id' => $this->sondaggi_id,
            'count' => $this->count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'query', $this->query]);

        return $dataProvider;
    }

    public static function searchOrganizations($params)
    {
        $query = Profilo::find();

        for ($i = 1; $i <= count($params['field']); $i++) {
            $operator = '=';
            if ($params['include_exclude'][$i] == SondaggiInvitations::FILTER_EXCLUDE) $operator = '!=';
            if ($params['field'][$i] == 'type') {
                $query = $query->joinWith('tipologiaDiOrganizzazione')->andFilterWhere([$operator, 'profilo_types_pmi.id',
                    $params['value'][$i]]);
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