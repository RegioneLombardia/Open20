<?php

namespace open20\amos\admin\models\base;

use Yii;
use open20\amos\core\record\Record;

/**
 * This is the base-model class for table "user_profile_classes_auth_mm".
 *
 * @property integer $id
 * @property integer $user_profile_classes_id
 * @property string $item_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\admin\models\UserProfileClasses $userProfileClasses
 */
class UserProfileClassesAuthMm extends Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile_classes_auth_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_profile_classes_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['item_id'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['item_id'], 'string', 'max' => 255],
            [['user_profile_classes_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\admin\models\UserProfileClasses::className(),
                'targetAttribute' => ['user_profile_classes_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosadmin', 'ID'),
            'user_profile_classes_id' => Yii::t('amosadmin', 'Profile'),
            'item_id' => Yii::t('amosadmin', 'Role/Permission'),
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
    public function getUserProfileClasses()
    {
        return $this->hasOne(\open20\amos\admin\models\UserProfileClasses::className(),
                ['id' => 'user_profile_classes_id']);
    }
}