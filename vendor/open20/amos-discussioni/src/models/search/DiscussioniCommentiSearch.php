<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\models\search;

use open20\amos\discussioni\models\DiscussioniCommenti;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DiscussioniCommentiSearch represents the model behind the search form about `open20\amos\discussioni\models\DiscussioniCommenti`.
 */
class DiscussioniCommentiSearch extends DiscussioniCommenti
{
    public function rules()
    {
        return [
            [['id', 'discussioni_risposte_id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['testo', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DiscussioniCommenti::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'discussioni_risposte_id' => $this->discussioni_risposte_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'testo', $this->testo]);

        return $dataProvider;
    }
}
