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
 * Class Comment
 * This is the base-model class for table "comment".
 *
 * @property integer $id
 * @property string $comment_text
 * @property string $context
 * @property integer $context_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\comments\models\CommentReply[] $commentReplies
 *
 * @package open20\amos\comments\models\base
 */
class Comment extends NotifyRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_text', 'context', 'context_id'], 'required'],
            [['comment_text'], 'string'],
            [['context'], 'string', 'max' => 255],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['context_id', 'created_by', 'updated_by', 'deleted_by'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosComments::t('amoscomments', 'ID'),
            'comment_text' => AmosComments::t('amoscomments', 'Comment Text'),
            'context' => AmosComments::t('amoscomments', 'Context'),
            'context_id' => AmosComments::t('amoscomments', 'Context ID'),
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
    public function getCommentReplies()
    {
        return $this->hasMany(\open20\amos\comments\models\CommentReply::className(), ['comment_id' => 'id']);
    }
}
