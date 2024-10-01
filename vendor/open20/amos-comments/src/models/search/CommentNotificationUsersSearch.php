<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\models\search
 * @category   CategoryName
 */

namespace open20\amos\comments\models\search;

use open20\amos\comments\models\base\CommentNotificationUsers;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class CommentNotificationUsersSearch
 * @package open20\amos\comments\models\search
 */
class CommentNotificationUsersSearch extends CommentNotificationUsers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'context_model_id', 'created_by', 'updated_by', 'deleted_by', 'enable'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'context_model_class_name'], 'safe'],
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
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        $query = CommentNotificationUsers::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'context_model_id' => $this->context_model_id,
            'enable' => $this->enable,
            'context_model_class_name' => $this->context_model_class_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        return $dataProvider;
    }
}
