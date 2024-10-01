<?php

namespace open20\amos\partnershipprofiles\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\partnershipprofiles\models\PartnershipProfilesCategory;

/**
* PartnershipProfilesCategorySearch represents the model behind the search form about `open20\amos\partnershipprofiles\models\PartnershipProfilesCategory`.
*/
class PartnershipProfilesCategorySearch extends PartnershipProfilesCategory
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
[['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'subtitle', 'short_description', 'description', 'color_text', 'color_background', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
];
}

public function scenarios()
{
// bypass scenarios() implementation in the parent class
return Model::scenarios();
}

public function search($params)
{
$query = PartnershipProfilesCategory::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);



$dataProvider->setSort([
'attributes' => [
    'title' => [
    'asc' => ['partnership_profiles_category.title' => SORT_ASC],
    'desc' => ['partnership_profiles_category.title' => SORT_DESC],
    ],
    'subtitle' => [
    'asc' => ['partnership_profiles_category.subtitle' => SORT_ASC],
    'desc' => ['partnership_profiles_category.subtitle' => SORT_DESC],
    ],
    'short_description' => [
    'asc' => ['partnership_profiles_category.short_description' => SORT_ASC],
    'desc' => ['partnership_profiles_category.short_description' => SORT_DESC],
    ],
    'description' => [
    'asc' => ['partnership_profiles_category.description' => SORT_ASC],
    'desc' => ['partnership_profiles_category.description' => SORT_DESC],
    ],
    'color_text' => [
    'asc' => ['partnership_profiles_category.color_text' => SORT_ASC],
    'desc' => ['partnership_profiles_category.color_text' => SORT_DESC],
    ],
    'color_background' => [
    'asc' => ['partnership_profiles_category.color_background' => SORT_ASC],
    'desc' => ['partnership_profiles_category.color_background' => SORT_DESC],
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

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'color_text', $this->color_text])
            ->andFilterWhere(['like', 'color_background', $this->color_background]);

return $dataProvider;
}
}
