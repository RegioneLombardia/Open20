<?php

namespace open20\amos\admin\models\base;

use open20\amos\core\user\User;
use Yii;

/**
 * This is the base-model class for table "user_access_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $access_type
 * @property string $access_method
 * @property string $access_level
 * @property integer $impersonator_user_id
 * @property string $be_or_fe
 * @property integer $enabled
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property User $user
 */
class  UserAccessLog extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_access_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['impersonator_user_id', 'user_id', 'access_type', 'enabled', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['be_or_fe', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['access_method', 'access_level'], 'string', 'max' => 255],
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
            'access_type' => Yii::t('amosadmin', 'Access type (1- login standard, 2- Idpc'),
            'access_method' => Yii::t('amosadmin', 'Access method'),
            'access_level' => Yii::t('amosadmin', 'Access Level'),
            'enabled' => Yii::t('amosadmin', 'Enabled'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImpersonatorUser()
    {
        return $this->hasOne(User::className(), ['id' => 'impersonator_user_id']);
    }
}
