<?php

namespace open20\amos\collaborations\models\base;

use Yii;

/**
 * This is the base-model class for table "collaboration_target_countries".
 *
 * @property integer $id
 * @property string $name
 * @property string $alpha_2
 * @property string $alpha_3
 * @property integer $country_code
 * @property string $iso_3166_2
 * @property string $region
 * @property string $sub_region
 * @property string $intermediate_region
 * @property integer $region_code
 * @property integer $sub_region_code
 * @property integer $intermediate_region_code
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 */
class  CollaborationTargetCountries extends \yii\db\ActiveRecord
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collaboration_target_countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alpha_2', 'alpha_3', 'country_code'], 'required'],
            [['country_code', 'region_code', 'sub_region_code', 'intermediate_region_code', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'iso_3166_2', 'region', 'sub_region', 'intermediate_region'], 'string', 'max' => 255],
            [['alpha_2'], 'string', 'max' => 2],
            [['alpha_3'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoscollaborations', 'ID'),
            'name' => Yii::t('amoscollaborations', 'Name'),
            'alpha_2' => Yii::t('amoscollaborations', 'Alpha 2 Code'),
            'alpha_3' => Yii::t('amoscollaborations', 'Alpha 3 Code'),
            'country_code' => Yii::t('amoscollaborations', 'Country Code'),
            'iso_3166_2' => Yii::t('amoscollaborations', 'ISO_3166-2'),
            'region' => Yii::t('amoscollaborations', 'Region'),
            'sub_region' => Yii::t('amoscollaborations', 'Sub-Region'),
            'intermediate_region' => Yii::t('amoscollaborations', 'Intermediate Region'),
            'region_code' => Yii::t('amoscollaborations', 'Region Code'),
            'sub_region_code' => Yii::t('amoscollaborations', 'Sub-Region Code'),
            'intermediate_region_code' => Yii::t('amoscollaborations', 'Intermediate Region Code'),
            'created_by' => Yii::t('amoscollaborations', 'Created By'),
            'created_at' => Yii::t('amoscollaborations', 'Created At'),
            'updated_by' => Yii::t('amoscollaborations', 'Updated By'),
            'updated_at' => Yii::t('amoscollaborations', 'Updated At'),
            'deleted_by' => Yii::t('amoscollaborations', 'Deleted By'),
            'deleted_at' => Yii::t('amoscollaborations', 'Deleted At'),
        ];
    }
}
