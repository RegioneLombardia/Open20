<?php

namespace open20\agid\person\models\search;

use open20\agid\person\models\AgidPersonContentType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AgidPersonContentTypeSearch represents the model behind the search form about `backend\modules\operators\models\AgidPersonContentType`.
 */
class AgidPersonContentTypeSearch extends AgidPersonContentType
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
        $query = AgidPersonContentType::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['agid_person_content_type.name' => SORT_ASC],
                    'desc' => ['agid_person_content_type.name' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['agid_person_content_type.description' => SORT_ASC],
                    'desc' => ['agid_person_content_type.description' => SORT_DESC],
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
