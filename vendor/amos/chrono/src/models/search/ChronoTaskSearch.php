<?php

namespace amos\chrono\models\search;

use amos\chrono\models\ChronoTask;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ChronoTaskSearch represents the model behind the search form about `amos\chrome\models\ChromeTask`.
 */
class ChronoTaskSearch extends ChronoTask
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
            [['name', 'task_date'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ChronoTask::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['name' => SORT_DESC],
            'attributes' => [
                'name' => [
                    'asc' => ['chrono_task.name' => SORT_ASC],
                    'desc' => ['chrono_task.name' => SORT_DESC],
                ],
                'task_date' => [
                    'asc' => ['chrono.task_date' => SORT_ASC],
                    'desc' => ['chrono_task.task_date' => SORT_DESC],
                ],
                'status' => [
                    'asc' => ['chrono_task.status' => SORT_ASC],
                    'desc' => ['chrono_task.status' => SORT_DESC],
                ],
        ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }



        $query->andFilterWhere([
            'id' => $this->id,
            'task_date' => $this->task_date,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}