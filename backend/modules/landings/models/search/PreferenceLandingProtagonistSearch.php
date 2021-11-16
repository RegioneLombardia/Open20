<?php

namespace backend\modules\landings\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\landings\models\PreferenceLandingProtagonist;

/**
 * PreferenceLandingProtagonistSearch represents the model behind the search form about `backend\modules\landings\models\PreferenceLandingProtagonist`.
 */
class PreferenceLandingProtagonistSearch extends PreferenceLandingProtagonist
{

//private $container; 

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'preference_landing_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'surname', 'company', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['EventLanding', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PreferenceLandingProtagonist::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('preferenceLanding');

        $dataProvider->setSort([
            'attributes' => [
                'preference_landing_id' => [
                    'asc' => ['preference_landing_protagonist.preference_landing_id' => SORT_ASC],
                    'desc' => ['preference_landing_protagonist.preference_landing_id' => SORT_DESC],
                ],
                'name' => [
                    'asc' => ['preference_landing_protagonist.name' => SORT_ASC],
                    'desc' => ['preference_landing_protagonist.name' => SORT_DESC],
                ],
                'surname' => [
                    'asc' => ['preference_landing_protagonist.surname' => SORT_ASC],
                    'desc' => ['preference_landing_protagonist.surname' => SORT_DESC],
                ],
                'company' => [
                    'asc' => ['preference_landing_protagonist.company' => SORT_ASC],
                    'desc' => ['preference_landing_protagonist.company' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'preference_landing_id' => $this->preference_landing_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'company', $this->company]);

        return $dataProvider;
    }
}
