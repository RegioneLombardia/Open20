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

use open20\amos\comments\models\base\CommentDashboard;
use open20\amos\comments\models\Comment;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class CommentDashboardSearch
 * @package open20\amos\comments\models\search
 */
class CommentDashboardSearch extends CommentDashboard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'netiquette_platform',
                'netiquette_communities',
                'moderation_platform',
                'moderation_communities',
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [[
                'created_at',
                'updated_at',
                'deleted_at'
            ], 'safe'],
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
        $query = CommentDashboard::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'netiquette_platform' => $this->netiquette_platform,
            'netiquette_communities' => $this->netiquette_communities,
            'moderation_platform' => $this->moderation_platform,
            'moderation_communities' => $this->moderation_communities,
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
