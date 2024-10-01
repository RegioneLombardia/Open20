<?php

namespace open20\amos\emailmanager\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\emailmanager\models\EmailView;

/**
 * EmailViewSearch represents the model behind the search form about `open20\amos\emailmanager\models\EmailView`.
 */
class EmailViewSearch extends EmailView
{
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['module', 'view', 'params', 'type', 'description'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = EmailView::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'module' => [
                    'asc' => ['email_view.module' => SORT_ASC],
                    'desc' => ['email_view.module' => SORT_DESC],
                ],
                'view' => [
                    'asc' => ['email_view.view' => SORT_ASC],
                    'desc' => ['email_view.view' => SORT_DESC],
                ],
                'params' => [
                    'asc' => ['email_view.params' => SORT_ASC],
                    'desc' => ['email_view.params' => SORT_DESC],
                ],
                'type' => [
                    'asc' => ['email_view.type' => SORT_ASC],
                    'desc' => ['email_view.type' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['email_view.description' => SORT_ASC],
                    'desc' => ['email_view.description' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'module', $this->module])
        ->andFilterWhere(['like', 'view', $this->view])
        ->andFilterWhere(['like', 'type', $this->type])
        ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
