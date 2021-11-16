<?php

namespace open20\amos\events\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\events\models\EventLocation;

/**
 * EventLocationSearch represents the model behind the search form about `open20\amos\events\models\EventLocation`.
 */
class EventLocationSearch extends EventLocation
{

    public $isSearch = true;

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
            [['name', 'description', 'event_place_id', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = EventLocation::find()
        ->andWhere(['!= ', 'hidden', true]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['event_location.name' => SORT_ASC],
                    'desc' => ['event_location.name' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['event_location.description' => SORT_ASC],
                    'desc' => ['event_location.description' => SORT_DESC],
                ],
                'event_place_id' => [
                    'asc' => ['event_location.event_place_id' => SORT_ASC],
                    'desc' => ['event_location.event_place_id' => SORT_DESC],
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
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'event_place_id', $this->event_place_id]);

        return $dataProvider;
    }
}
