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

use open20\amos\comments\models\base\CommentBlacklist;
use open20\amos\comments\models\Comment;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class CommentBlacklistSearch
 * @package open20\amos\comments\models\search
 */
class CommentBlacklistSearch extends CommentBlacklist
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['id', 'created_by', 'updated_by', 'deleted_by'],
                'integer'
            ],
            [
                ['word', 'created_at', 'updated_at', 'deleted_at'],
                'safe'
            ],
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
        $query = CommentBlacklist::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

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

        $query->andFilterWhere(['like', 'word', $this->word]);

        return $dataProvider;
    }
}
