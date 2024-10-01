<?php

namespace amos\podcast\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amos\podcast\models\PodcastCategory;

/**
* PodcastCategorySearch represents the model behind the search form about `amos\podcast\models\PodcastCategory`.
*/
class PodcastCategorySearch extends PodcastCategory
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
            [['name', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
];
}

public function scenarios()
{
// bypass scenarios() implementation in the parent class
return Model::scenarios();
}

public function search($params)
{
$query = PodcastCategory::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);



$dataProvider->setSort([
'attributes' => [
    'name' => [
    'asc' => ['podcast_category.name' => SORT_ASC],
    'desc' => ['podcast_category.name' => SORT_DESC],
    ],
    'description' => [
    'asc' => ['podcast_category.description' => SORT_ASC],
    'desc' => ['podcast_category.description' => SORT_DESC],
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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

return $dataProvider;
}
}
