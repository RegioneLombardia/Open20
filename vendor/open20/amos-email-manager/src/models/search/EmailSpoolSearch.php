<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

namespace open20\amos\emailmanager\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\emailmanager\models\EmailSpool;

/**
 * EmailSpoolSearch represents the model behind the search form about `open20\amos\emailmanager\models\EmailSpool`.
 */
class EmailSpoolSearch extends EmailSpool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority', 'model_id', 'sent', 'created_at', 'updated_at'], 'integer'],
            [['transport', 'template', 'status', 'model_name', 'to_address', 'from_address', 'subject', 'message', 'bcc', 'files'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EmailSpool::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'priority' => $this->priority,
            'model_id' => $this->model_id,
            'sent' => $this->sent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'transport', $this->transport])
            ->andFilterWhere(['like', 'template', $this->template])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'model_name', $this->model_name])
            ->andFilterWhere(['like', 'to_address', $this->to_address])
            ->andFilterWhere(['like', 'from_address', $this->from_address])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'bcc', $this->bcc])
            ->andFilterWhere(['like', 'files', $this->files]);

        return $dataProvider;
    }
}
