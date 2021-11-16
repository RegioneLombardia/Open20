<?php

namespace open20\amos\sondaggi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\sondaggi\models\SondaggiDomande;

/**
 * SondaggiDomandeSearch represents the model behind the search form about `open20\amos\sondaggi\models\SondaggiDomande`.
 */
class SondaggiDomandeSearch extends SondaggiDomande {

    public function rules() {
        return [
            [['id', 'risposta_predefinita', 'riposta_multipla', 'domanda_condizionata', 'sondaggi_id', 'ordinamento', 'sondaggi_domande_pagine_id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['domanda', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios() {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = SondaggiDomande::find();    
        if(isset($params['idSondaggio'])){
            $query->andWhere(['sondaggi_id' => $params['idSondaggio']]);
        }
        if(isset($params['idPagina'])){
            $query->andWhere(['sondaggi_domande_pagine_id' => $params['idPagina']]);
        }
        $query->orderBy('sondaggi_domande_pagine_id, ordinamento ASC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'risposta_predefinita' => $this->risposta_predefinita,
            'riposta_multipla' => $this->riposta_multipla,
            'domanda_condizionata' => $this->domanda_condizionata,
            'sondaggi_id' => $this->sondaggi_id,
            'ordinamento' => $this->ordinamento,
            'sondaggi_domande_pagine_id' => $this->sondaggi_domande_pagine_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'domanda', $this->domanda]);

        return $dataProvider;
    }

}
