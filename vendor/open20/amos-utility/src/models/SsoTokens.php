<?php

namespace open20\amos\utility\models;

use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use Yii;


/**
 * This is the base-model class for table "interoperability_tokens".
 *
 * @property integer $id
 * @property string $token
 * @property integer $user_id
 * @property string $domain
 * @property integer $impersonate
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 * @property User $user
 */
class SsoTokens extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'utility_sso_tokens';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'deleted_by', 'user_id', 'impersonate'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['token', 'domain'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosutility', 'ID'),
            'token' => Yii::t('amosutility', 'Token'),
            'user_id' => Yii::t('amosutility', 'User ID'),
            'domain' => Yii::t('amosutility', 'Domain'),
            'impersonate' => Yii::t('amosutility', 'Impersonate'),
            'created_by' => Yii::t('amosutility', 'Created By'),
            'created_at' => Yii::t('amosutility', 'Created At'),
            'updated_by' => Yii::t('amosutility', 'Updated By'),
            'updated_at' => Yii::t('amosutility', 'Updated At'),
            'deleted_by' => Yii::t('amosutility', 'Deleted By'),
            'deleted_at' => Yii::t('amosutility', 'Deleted At'),
        ];
    }

    public function fields()
    {
        return [
            'token',
            'user_id',
            'impersonate',
            'user' => function (SsoTokens $token) {
                return $token->user
                    ->toArray([
                                  'username',
                                  'email'
                              ]);
            }
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
