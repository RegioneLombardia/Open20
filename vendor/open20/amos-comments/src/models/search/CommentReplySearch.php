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

use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\comments\utility\CommentsUtility;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class CommentReplySearch
 * @package open20\amos\comments\models\search
 */
class CommentReplySearch extends CommentReply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'comment_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['comment_reply_text', 'context', 'created_at', 'updated_at', 'deleted_at', 'status'], 'safe'],
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

    public function search($params)
    {
        $query = CommentReply::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'comment_id' => $this->comment_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'comment_reply_text', $this->comment_reply_text]);

        return $dataProvider;
    }

    /**
     * Search comment replies to validate
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchToValidate($params): ActiveDataProvider
    {
        // Checks if the user is a validator of the context model
        $commentRepliesToValidate = CommentReply::find()->andWhere(['status' => Comment::STATUS_TOVALIDATE])->all();
        $commentReplyIdsToShow = [];
        /** @var CommentReply $commentReplyToValidate */
        foreach ($commentRepliesToValidate as $commentReplyToValidate) {
            $contextModel = $commentReplyToValidate->comment->contextModel;
            if (CommentsUtility::userIsCommentsValidator($contextModel)) {
                $commentReplyIdsToShow[] = $commentReplyToValidate->id;
            }
        }
        if (!empty($commentReplyIdsToShow)) {
            $dataProvider = $this->search($params);
            $dataProvider->query->andWhere(['status' => Comment::STATUS_TOVALIDATE]);
            $dataProvider->query->andWhere(['in', 'id', $commentReplyIdsToShow]);
        } else {
            // If the user is not a validator, the dataProvider is empty
            $dataProvider = new ActiveDataProvider([
                'query' => Comment::find()->andWhere(['id' => 0]),
            ]);
        }

        return $dataProvider;
    }
}
