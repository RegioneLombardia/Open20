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
use open20\amos\comuni\models\IstatProvince;

/**
 * IstatProvinceSearch represents the model behind the search form about `open20\amos\comuni\models\IstatProvince`.
 */
class IstatProvinceSearch extends IstatProvince
{
    public function rules()
    {
        return [
            [['id', 'capoluogo', 'soppressa', 'istat_citta_metropolitane_id', 'istat_regioni_id'], 'integer'],
            [['nome', 'sigla'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = IstatProvince::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'capoluogo' => $this->capoluogo,
            'soppressa' => $this->soppressa,
            'istat_citta_metropolitane_id' => $this->istat_citta_metropolitane_id,
            'istat_regioni_id' => $this->istat_regioni_id,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'sigla', $this->sigla]);

        return $dataProvider;
    }
}
