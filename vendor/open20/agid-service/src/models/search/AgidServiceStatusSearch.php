<?php

namespace open20\agid\service\models\search;

use open20\agid\service\models\AgidServiceStatus;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AgidServiceStatusSearch represents the model behind the search form about `open20\agid\service\models\AgidServiceStatus`.
 */
class AgidServiceStatusSearch extends AgidServiceStatus
{
    public $isSearch;

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
        $query = AgidServiceStatus::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['agid_service_status.name' => SORT_ASC],
                    'desc' => ['agid_service_status.name' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['agid_service_status.description' => SORT_ASC],
                    'desc' => ['agid_service_status.description' => SORT_DESC],
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
