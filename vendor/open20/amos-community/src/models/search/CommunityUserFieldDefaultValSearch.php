<?php

namespace open20\amos\community\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\community\models\CommunityUserFieldDefaultVal;

/**
 * CommunityUserFieldDefaultValSearch represents the model behind the search form about `open20\amos\community\models\CommunityUserFieldDefaultVal`.
 */
class CommunityUserFieldDefaultValSearch extends CommunityUserFieldDefaultVal
{

    public $isSearch;
//private $container; 

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['community_user_field_id', 'value', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CommunityUserFieldDefaultVal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'community_user_field_id' => [
                    'asc' => ['community_user_field_default_val.community_user_field_id' => SORT_ASC],
                    'desc' => ['community_user_field_default_val.community_user_field_id' => SORT_DESC],
                ],
                'value' => [
                    'asc' => ['community_user_field_default_val.value' => SORT_ASC],
                    'desc' => ['community_user_field_default_val.value' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'community_user_field_id', $this->community_user_field_id])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
