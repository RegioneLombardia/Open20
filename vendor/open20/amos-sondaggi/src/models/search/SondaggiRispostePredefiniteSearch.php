<?php

namespace open20\amos\sondaggi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\sondaggi\models\SondaggiRispostePredefinite;

/**
 * SondaggiRispostePredefiniteSearch represents the model behind the search form about `open20\amos\sondaggi\models\SondaggiRispostePredefinite`.
 */
class SondaggiRispostePredefiniteSearch extends SondaggiRispostePredefinite {

    public function rules() {
        return [
            [['id', 'sondaggi_domande_id', 'ordinamento', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['risposta', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios() {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = SondaggiRispostePredefinite::find();
        if (isset($params['idDomanda'])) {
            $query->andWhere(['sondaggi_domande_id' => $params['idDomanda']])->orderBy('ordinamento ASC');
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'sondaggi_domande_id' => $this->sondaggi_domande_id,
            'ordinamento' => $this->ordinamento,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'risposta', $this->risposta]);

        return $dataProvider;
    }

}
