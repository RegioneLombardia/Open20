<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\models\base
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\models\base;

use open20\amos\core\record\Record;
use open20\amos\partnershipprofiles\Module;
use yii\helpers\ArrayHelper;

/**
 * Class PartnershipProfilesTypesMm
 *
 * This is the base-model class for table "partnership_profiles_types_mm".
 *
 * @property integer $id
 * @property integer $partnership_profile_id
 * @property integer $partnership_profiles_type_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\partnershipprofiles\models\PartnershipProfiles $partnershipProfile
 * @property \open20\amos\partnershipprofiles\models\PartnershipProfilesType $partnershipProfilesType
 *
 * @package open20\amos\partnershipprofiles\models\base
 */
class PartnershipProfilesTypesMm extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partnership_profiles_types_mm';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partnership_profile_id', 'partnership_profiles_type_id'], 'required'],
            [['partnership_profile_id', 'partnership_profiles_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['partnership_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\partnershipprofiles\models\PartnershipProfiles::className(), 'targetAttribute' => ['partnership_profile_id' => 'id']],
            [['partnership_profiles_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\partnershipprofiles\models\PartnershipProfilesType::className(), 'targetAttribute' => ['partnership_profiles_type_id' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amospartnershipprofiles', 'ID'),
            'partnership_profile_id' => Module::t('amospartnershipprofiles', 'Partnership Profile ID'),
            'partnership_profiles_type_id' => Module::t('amospartnershipprofiles', 'Partnership Profiles Type ID'),
            'created_at' => Module::t('amospartnershipprofiles', 'Created at'),
            'updated_at' => Module::t('amospartnershipprofiles', 'Updated at'),
            'deleted_at' => Module::t('amospartnershipprofiles', 'Deleted at'),
            'created_by' => Module::t('amospartnershipprofiles', 'Created by'),
            'updated_by' => Module::t('amospartnershipprofiles', 'Updated by'),
            'deleted_by' => Module::t('amospartnershipprofiles', 'Deleted by')
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnershipProfile()
    {
        return $this->hasOne(Module::instance()->model('PartnershipProfiles'), ['id' => 'partnership_profile_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnershipProfilesType()
    {
        return $this->hasOne(\open20\amos\partnershipprofiles\models\PartnershipProfilesType::className(), ['id' => 'partnership_profiles_type_id']);
    }
}
