<?php

namespace open20\elasticsearch\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\elasticsearch\models\ElasticsearchHistory;

/**
 * ElasticsearchHistorySearch represents the model behind the search form about `open20\elasticsearch\models\ElasticsearchHistory`.
 */
class ElasticsearchHistorySearch extends ElasticsearchHistory
{

    public $dateFrom;

//private $container; 

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['dateFrom', 'search_text', 'results', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['User', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ElasticsearchHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('user');

        $dataProvider->setSort([
            'defaultOrder' => [ 'created_at' => SORT_DESC],
            'attributes' => [
                'search_text' => [
                    'asc' => ['elasticsearch_history.search_text' => SORT_ASC],
                    'desc' => ['elasticsearch_history.search_text' => SORT_DESC],
                ],
                'created_at' => [
                    'asc' => ['elasticsearch_history.created_at' => SORT_ASC],
                    'desc' => ['elasticsearch_history.created_at' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'elasticsearch_history.user_id' => $this->user_id,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'elasticsearch_history.search_text', $this->search_text])
            ->andFilterWhere(['like', 'elasticsearch_history.results', $this->results])
            ->andFilterWhere(['>=', 'elasticsearch_history.created_at', $this->dateFrom]);

        return $dataProvider;
    }
}
