<?php

namespace open20\amos\events\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\events\models\EventLocationEntrances;

/**
 * EventLocationEntrancesSearch represents the model behind the search form about `open20\amos\events\models\EventLocationEntrances`.
 */
class EventLocationEntrancesSearch extends EventLocationEntrances
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
            [['id', 'event_location_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['EventLocation', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = EventLocationEntrances::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('eventLocation');

        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['event_location_entrances.name' => SORT_ASC],
                    'desc' => ['event_location_entrances.name' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['event_location_entrances.description' => SORT_ASC],
                    'desc' => ['event_location_entrances.description' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'event_location_id' => $this->event_location_id,
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
