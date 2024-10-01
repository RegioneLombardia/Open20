<?php

namespace open20\amos\partnershipprofiles\models\base;

use Yii;

/**
 * This is the base-model class for table "partnership_profiles_category_mm".
 *
 * @property integer $id
 * @property integer $partnership_profiles_category_id
 * @property integer $partnership_profiles_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  PartnershipProfilesCategoryMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partnership_profiles_category_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partnership_profiles_id', 'partnership_profiles_category_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amospartnershipprofiles', 'ID'),
            'partnership_profiles_category_id' => Yii::t('amospartnershipprofiles', 'Category'),
            'partnership_profiles_id' => Yii::t('amospartnershipprofiles', 'Proposal'),
            'created_at' => Yii::t('amospartnershipprofiles', 'Created at'),
            'updated_at' => Yii::t('amospartnershipprofiles', 'Updated at'),
            'deleted_at' => Yii::t('amospartnershipprofiles', 'Deleted at'),
            'created_by' => Yii::t('amospartnershipprofiles', 'Created by'),
            'updated_by' => Yii::t('amospartnershipprofiles', 'Updated by'),
            'deleted_by' => Yii::t('amospartnershipprofiles', 'Deleted by'),
        ];
    }


}
