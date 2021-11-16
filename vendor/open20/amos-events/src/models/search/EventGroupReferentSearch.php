<?php

namespace open20\amos\events\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\events\models\EventGroupReferent;

/**
* EventGroupReferentSearch represents the model behind the search form about `open20\amos\events\models\EventGroupReferent`.
*/
class EventGroupReferentSearch extends EventGroupReferent
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
            [['denominazione', 'descrizione', 'note', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
];
}

public function scenarios()
{
// bypass scenarios() implementation in the parent class
return Model::scenarios();
}

public function search($params)
{
$query = EventGroupReferent::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);



$dataProvider->setSort([
'attributes' => [
    'denominazione' => [
    'asc' => ['event_group_referent.denominazione' => SORT_ASC],
    'desc' => ['event_group_referent.denominazione' => SORT_DESC],
    ],
    'descrizione' => [
    'asc' => ['event_group_referent.descrizione' => SORT_ASC],
    'desc' => ['event_group_referent.descrizione' => SORT_DESC],
    ],
    'note' => [
    'asc' => ['event_group_referent.note' => SORT_ASC],
    'desc' => ['event_group_referent.note' => SORT_DESC],
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

        $query->andFilterWhere(['like', 'denominazione', $this->denominazione])
            ->andFilterWhere(['like', 'descrizione', $this->descrizione])
            ->andFilterWhere(['like', 'note', $this->note]);

return $dataProvider;
}
}
