<?php

namespace backend\modules\landings\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\landings\models\PreferenceLandingLink;

/**
 * PreferenceLinksSearch represents the model behind the search form about `backend\modules\landings\models\PreferenceLandingLink`.
 */
class PreferenceLandingLinkSearch extends PreferenceLandingLink
{

//private $container; 

    public function __construct(array $config = [])
    {
//        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'preference_landing_id', 'sort_value', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'url', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['PreferenceLanding', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PreferenceLandingLink::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('preferenceLanding');

        $dataProvider->setSort([
            'attributes' => [
//                'title' => [
//                    'asc' => ['preference_landing_link.title' => SORT_ASC],
//                    'desc' => ['preference_landing_link.title' => SORT_DESC],
//                ],
//                'url' => [
//                    'asc' => ['preference_landing_link.url' => SORT_ASC],
//                    'desc' => ['preference_landing_link.url' => SORT_DESC],
//                ],
//                'preferenceLanding' => [
//                    'asc' => ['preference_landing.preference_structure_id' => SORT_ASC],
//                    'desc' => ['preference_landing.preference_structure_id' => SORT_DESC],
//                ],
                ],
            'defaultOrder' => [
                'sort_value' => SORT_ASC
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'preference_landing_id' => $this->preference_landing_id,
            'sort_value' => $this->sort_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['like', new \yii\db\Expression('preference_landing.preference_structure_id'), $this->PreferenceLanding]);

        return $dataProvider;
    }
}
