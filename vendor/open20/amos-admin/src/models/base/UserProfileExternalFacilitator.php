<?php

namespace open20\amos\admin\models\base;

use open20\amos\admin\models\UserProfile;
use Yii;

/**
 * This is the base-model class for table "user_profile_external_facilitator".
 *
 * @property integer $id
 * @property integer $user_profile_id
 * @property integer $external_facilitator_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property UserProfile $externalFacilitator
 * @property UserProfile $externalFacilitator0
 */
class  UserProfileExternalFacilitator extends \open20\amos\core\record\Record
{

    const EXTERNAL_FACILITATOR_REQUEST = 1;
    const EXTERNAL_FACILITATOR_ACCEPTED = 2;
    const EXTERNAL_FACILITATOR_REJECTED = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile_external_facilitator';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_profile_id', 'external_facilitator_id', 'status', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['external_facilitator_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserProfile::className(), 'targetAttribute' => ['external_facilitator_id' => 'id']],
            [['external_facilitator_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserProfile::className(), 'targetAttribute' => ['external_facilitator_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosadmin', 'ID'),
            'user_profile_id' => Yii::t('amosadmin', 'User profile'),
            'external_facilitator_id' => Yii::t('amosadmin', 'Facilitator'),
            'status' => Yii::t('amosadmin', 'Status'),
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
    public function getExternalFacilitator()
    {
        return $this->hasOne(UserProfile::className(), ['id' => 'external_facilitator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), ['id' => 'user_profile_id']);
    }
}
