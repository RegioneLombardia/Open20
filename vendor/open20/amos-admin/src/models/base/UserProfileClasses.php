<?php

namespace open20\amos\admin\models\base;

use open20\amos\core\validators\StringHtmlValidator;
use Yii;
use open20\amos\core\record\Record;

/**
 * This is the base-model class for table "user_profile_classes".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $code
 * @property integer $enabled
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\admin\models\UserProfileClassesAuthMm[] $userProfileClassesAuthMms
 * @property \open20\amos\admin\models\UserProfileClassesUserMm[] $userProfileClassesUserMms
 */
class UserProfileClasses extends Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile_classes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['description'], StringHtmlValidator::className()],
            [['enabled', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosadmin', 'ID'),
            'name' => Yii::t('amosadmin', 'Name'),
            'description' => Yii::t('amosadmin', 'Description'),
            'code' => Yii::t('amosadmin', 'Code'),
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
    public function getUserProfileClassesAuthMms()
    {
        return $this->hasMany(\open20\amos\admin\models\UserProfileClassesAuthMm::className(),
                ['user_profile_classes_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfileClassesUserMms()
    {
        return $this->hasMany(\open20\amos\admin\models\UserProfileClassesUserMm::className(),
                ['user_profile_classes_id' => 'id']);
    }
}