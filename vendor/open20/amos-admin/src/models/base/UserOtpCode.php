<?php

namespace open20\amos\admin\models\base;

use open20\amos\core\user\User;
use Yii;

/**
 * This is the base-model class for table "user_otp_code".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $session_id
 * @property string $otp_code
 * @property string $type
 * @property string $expire
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\admin\models\User $user
 */
class  UserOtpCode extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_otp_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['auth_code','expire', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['session_id', 'otp_code', 'type'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosadmin', 'ID'),
            'user_id' => Yii::t('amosadmin', 'User'),
            'session_id' => Yii::t('amosadmin', 'Session'),
            'otp_code' => Yii::t('amosadmin', 'OTP'),
            'type' => Yii::t('amosadmin', 'Type'),
            'expire' => Yii::t('amosadmin', 'Expire at'),
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
