<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\comuni\models\IstatNazioni;

/**
 * IstatNazioniSearch represents the model behind the search form about `open20\amos\comuni\models\IstatNazioni`.
 */
class IstatNazioniSearch extends IstatNazioni
{
    public function rules()
    {
        return [
            [['id', 'area', 'unione_europea', 'istat_continenti_id'], 'integer'],
            [['nome', 'nome_inglese'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = IstatNazioni::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'area' => $this->area,
            'unione_europea' => $this->unione_europea,
            'istat_continenti_id' => $this->istat_continenti_id,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'nome_inglese', $this->nome_inglese]);

        return $dataProvider;
    }
}
