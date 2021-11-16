<?php

namespace open20\amos\community\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\community\models\CommunityUserField;

/**
 * CommunityUserFieldSearch represents the model behind the search form about `open20\amos\community\models\CommunityUserField`.
 */
class CommunityUserFieldSearch extends CommunityUserField
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
            [['id', 'community_id', 'user_field_type_id', 'required', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'tooltip', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['Community', 'safe'],
            ['fieldType', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CommunityUserField::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('fieldType');

        $dataProvider->setSort([
            'attributes' => [
                'community_id' => [
                    'asc' => ['community_user_field.community_id' => SORT_ASC],
                    'desc' => ['community_user_field.community_id' => SORT_DESC],
                ],
                'user_field_type_id' => [
                    'asc' => ['community_user_field.user_field_type_id' => SORT_ASC],
                    'desc' => ['community_user_field.user_field_type_id' => SORT_DESC],
                ],
                'name' => [
                    'asc' => ['community_user_field.name' => SORT_ASC],
                    'desc' => ['community_user_field.name' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['community_user_field.description' => SORT_ASC],
                    'desc' => ['community_user_field.description' => SORT_DESC],
                ],
                'tooltip' => [
                    'asc' => ['community_user_field.tooltip' => SORT_ASC],
                    'desc' => ['community_user_field.tooltip' => SORT_DESC],
                ],
                'required' => [
                    'asc' => ['community_user_field.required' => SORT_ASC],
                    'desc' => ['community_user_field.required' => SORT_DESC],
                ],
                'community' => [
                    'asc' => ['community.name' => SORT_ASC],
                    'desc' => ['community.name' => SORT_DESC],
                ], 'fieldType' => [
                    'asc' => ['community_user_field_type.name' => SORT_ASC],
                    'desc' => ['community_user_field_type.name' => SORT_DESC],
                ],]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'community_id' => $this->community_id,
            'user_field_type_id' => $this->user_field_type_id,
            'required' => $this->required,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tooltip', $this->tooltip]);
        $query->andFilterWhere(['like', new \yii\db\Expression('community.name'), $this->Community]);
        $query->andFilterWhere(['like', new \yii\db\Expression('community_user_field_type.name'), $this->fieldType]);

        return $dataProvider;
    }
}
