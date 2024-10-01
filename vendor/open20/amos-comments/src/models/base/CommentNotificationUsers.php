<?php

namespace open20\amos\comments\models\base;

use open20\amos\comments\AmosComments;
use open20\amos\core\user\User;
use Yii;

/**
 * This is the base-model class for table "comment_disabled_notification_users".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $context_model_class_name
 * @property integer $context_model_id
 * @property boolean $enable
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property User;
 */
class CommentNotificationUsers extends \open20\amos\core\record\Record
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'comment_notification_users';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['user_id', 'context_model_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['context_model_class_name'], 'string'],
            [['enable'], 'boolean'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return [
            'id' => AmosComments::t('amoscomments', 'ID'),
            'user_id' => AmosComments::t('amoscomments', 'User ID'),
            'context_model_id' => AmosComments::t('amoscomments', 'Content Model ID'),
            'context_model_class_name' => AmosComments::t('amoscomments', 'Context Model Class Name'),
            'enable' => AmosComments::t('amoscomments', 'Enable'),
            'created_at' => AmosComments::t('amoscomments', 'Created at'),
            'updated_at' => AmosComments::t('amoscomments', 'Updated at'),
            'deleted_at' => AmosComments::t('amoscomments', 'Deleted at'),
            'created_by' => AmosComments::t('amoscomments', 'Created by'),
            'updated_by' => AmosComments::t('amoscomments', 'Updated by'),
            'deleted_by' => AmosComments::t('amoscomments', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
