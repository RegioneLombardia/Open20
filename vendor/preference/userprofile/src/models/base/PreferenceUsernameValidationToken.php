<?php

namespace preference\userprofile\models\base;

use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use preference\userprofile\exceptions\NotificationEmailException;
use preference\userprofile\utility\EmailUtility;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the base-model class for table "preference_username_validation_token".
 *
 * @property integer $id
 * @property string $email
 * @property string $email_validation_token
 * @property integer $validated_email_flag
 * @property string $phone
 * @property string $phone_validation_token
 * @property integer $validated_phone_flag
 * @property string $target_code
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class PreferenceUsernameValidationToken extends Record
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preference_username_validation_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['token'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('preferenceuser', 'ID'),
            'token' => Yii::t('preferenceuser', 'Token'),
            'created_at' => Yii::t('preferenceuser', 'Created at'),
            'updated_at' => Yii::t('preferenceuser', 'Updated at'),
            'deleted_at' => Yii::t('preferenceuser', 'Deleted at'),
            'created_by' => Yii::t('preferenceuser', 'Created by'),
            'updated_by' => Yii::t('preferenceuser', 'Updated by'),
            'deleted_by' => Yii::t('preferenceuser', 'Deleted by'),
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
