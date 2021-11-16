<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\models\search
 * @category   CategoryName
 */

namespace open20\amos\comments\models\search;

use open20\amos\comments\models\CommentReply;
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
            [['comment_reply_text', 'context', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
}
