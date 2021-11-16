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
use yii\helpers\ArrayHelper;

/**
 * Class CommentContextAttribute
 * This is the base-model class for table "comment_context_attribute".
 *
 * @property integer $id
 * @property integer $commentable_context
 * @property string $context
 * @property integer $context_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package open20\amos\comments\models\base
 */
class CommentContextAttribute extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_context_attribute';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['commentable_context', 'context', 'context_id'], 'required'],
            [['commentable_context'], 'boolean'],
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
            'commentable_context' => AmosComments::t('amoscomments', 'Commentable Context'),
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
}
