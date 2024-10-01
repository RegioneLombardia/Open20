<?php

namespace open20\amos\admin\models\base;

use open20\amos\admin\AmosAdmin;
use Yii;

/**
 * This is the base-model class for table "user_profile_reactivation_request".
 *
 * @property integer $id
 * @property integer $user_profile_id
 * @property string $message
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\admin\models\UserProfile $userProfile
 */
class UserProfileReactivationRequest extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile_reactivation_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_profile_id'], 'required'],
            [['user_profile_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['user_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserProfile::className(), 'targetAttribute' => ['user_profile_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosadmin', 'ID'),
            'user_profile_id' => Yii::t('amosadmin', 'User Profile ID'),
            'message' => Yii::t('amosadmin', 'Message'),
            'created_at' => Yii::t('amosadmin', 'Created at'),
            'updated_at' => Yii::t('amosadmin', 'Updated at'),
            'deleted_at' => Yii::t('amosadmin', 'Deleted at'),
            'created_by' => Yii::t('amosadmin', 'Created by'),
            'updated_by' => Yii::t('amosadmin', 'Updated at'),
            'deleted_by' => Yii::t('amosadmin', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(AmosAdmin::instance()->model('UserProfile'), ['id' => 'user_profile_id']);
    }
}
