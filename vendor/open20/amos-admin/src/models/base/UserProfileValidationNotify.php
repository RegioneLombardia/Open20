<?php

namespace open20\amos\admin\models\base;

use open20\amos\admin\AmosAdmin;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "user_profile_validation_notify".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\core\user\User $user
 */
class UserProfileValidationNotify extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile_validation_notify';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['user_id', 'status', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosAdmin::t('amosadmin', 'ID'),
            'user_id' => AmosAdmin::t('amosadmin', 'User profile'),
            'status' => AmosAdmin::t('amosadmin', 'Status'),
            'created_at' => AmosAdmin::t('amosadmin', 'Created at'),
            'updated_at' => AmosAdmin::t('amosadmin', 'Updated at'),
            'deleted_at' => AmosAdmin::t('amosadmin', 'Deleted at'),
            'created_by' => AmosAdmin::t('amosadmin', 'Created by'),
            'updated_by' => AmosAdmin::t('amosadmin', 'Updated at'),
            'deleted_by' => AmosAdmin::t('amosadmin', 'Deleted at'),
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(AmosAdmin::instance()->model('User'), ['id' => 'user_id']);
    }
}
