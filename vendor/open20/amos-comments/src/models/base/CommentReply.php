<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\models\base
 * @category   CategoryName
 */

namespace open20\amos\comments\models\base;

use open20\amos\comments\AmosComments;
use open20\amos\notificationmanager\record\NotifyRecord;
use yii\helpers\ArrayHelper;

/**
 * Class CommentReply
 * This is the base-model class for table "comment_reply".
 *
 * @property integer $id
 * @property string $comment_reply_text
 * @property integer $comment_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\comments\models\Comment $comment
 *
 * @package open20\amos\comments\models\base
 */
class CommentReply extends NotifyRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_reply';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_reply_text', 'comment_id'], 'required'],
            [['comment_reply_text'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['comment_id', 'created_by', 'updated_by', 'deleted_by'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosComments::t('amoscomments', 'ID'),
            'comment_reply_text' => AmosComments::t('amoscomments', 'Comment Reply Text'),
            'comment_id' => AmosComments::t('amoscomments', 'Comment ID'),
            'created_at' => AmosComments::t('amoscomments', 'Created At'),
            'updated_at' => AmosComments::t('amoscomments', 'Updated At'),
            'deleted_at' => AmosComments::t('amoscomments', 'Deleted At'),
            'created_by' => AmosComments::t('amoscomments', 'Created By'),
            'updated_by' => AmosComments::t('amoscomments', 'Updated By'),
            'deleted_by' => AmosComments::t('amoscomments', 'Deleted By')
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(\open20\amos\comments\models\Comment::className(), ['id' => 'comment_id']);
    }
}
