<?php

namespace open20\amos\partnershipprofiles\models\base;

use Yii;

/**
 * This is the base-model class for table "partnership_profiles_category_roles".
 *
 * @property integer $id
 * @property integer $partnership_profiles_category_id
 * @property string $role
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  PartnershipProfilesCategoryRoles extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partnership_profiles_category_roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partnership_profiles_category_id', 'role'], 'required'],
            [['partnership_profiles_category_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['role'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('partnershipprofiles', 'ID'),
            'partnership_profiles_category_id' => Yii::t('partnershipprofiles', 'Category'),
            'role' => Yii::t('partnershipprofiles', 'Role'),
            'created_at' => Yii::t('partnershipprofiles', 'Created at'),
            'updated_at' => Yii::t('partnershipprofiles', 'Updated at'),
            'deleted_at' => Yii::t('partnershipprofiles', 'Deleted at'),
            'created_by' => Yii::t('partnershipprofiles', 'Created by'),
            'updated_by' => Yii::t('partnershipprofiles', 'Updated by'),
            'deleted_by' => Yii::t('partnershipprofiles', 'Deleted by'),
        ];
    }
}
