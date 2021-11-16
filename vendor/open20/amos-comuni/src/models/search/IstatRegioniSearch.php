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
use open20\amos\comuni\models\IstatRegioni;

/**
 * IstatRegioniSearch represents the model behind the search form about `open20\amos\comuni\models\IstatRegioni`.
 */
class IstatRegioniSearch extends IstatRegioni
{
    public function rules()
    {
        return [
            [['id', 'istat_nazioni_id'], 'integer'],
            [['nome'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = IstatRegioni::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'istat_nazioni_id' => $this->istat_nazioni_id,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome]);

        return $dataProvider;
    }
}
