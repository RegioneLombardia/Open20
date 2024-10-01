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

use open20\amos\admin\AmosAdmin;
use open20\amos\community\models\CommunityInterface;
use open20\amos\core\record\ContentModel;
use open20\amos\core\validators\StringHtmlValidator;
use open20\amos\partnershipprofiles\Module;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class PartnershipProfiles
 *
 * This is the base-model class for table "partnership_profiles".
 *
 * @property integer $id
 * @property string $status
 * @property string $title
 * @property string $short_description
 * @property string $extended_description
 * @property string $advantages_innovative_aspects
 * @property string $other_prospect_desired_collab
 * @property string $expected_contribution
 * @property string $contact_person
 * @property string $partnership_profile_date
 * @property string $expiration_in_months
 * @property string $english_title
 * @property string $english_short_description
 * @property string $english_extended_description
 * @property string $willingness_foreign_partners
 * @property string $other_work_language
 * @property string $other_development_stage
 * @property string $other_intellectual_property
 * @property integer $validated_at_least_once
 * @property integer $partnership_profile_facilitator_id
 * @property integer $work_language_id
 * @property integer $development_stage_id
 * @property integer $intellectual_property_id
 * @property integer $community_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\partnershipprofiles\models\ExpressionsOfInterest[] $expressionsOfInterest
 * @property \open20\amos\partnershipprofiles\models\ExpressionsOfInterest[] $draftExpressionsOfInterest
 * @property \open20\amos\partnershipprofiles\models\ExpressionsOfInterest[] $activeExpressionsOfInterest
 * @property \open20\amos\partnershipprofiles\models\ExpressionsOfInterest[] $toValidateExpressionsOfInterest
 * @property \open20\amos\partnershipprofiles\models\ExpressionsOfInterest[] $relevantExpressionsOfInterest
 * @property \open20\amos\partnershipprofiles\models\ExpressionsOfInterest[] $rejectedExpressionsOfInterest
 * @property \open20\amos\partnershipprofiles\models\ExpressionsOfInterest[] $notDraftExpressionsOfInterest
 * @property \open20\amos\partnershipprofiles\models\WorkLanguage $workLanguage
 * @property \open20\amos\partnershipprofiles\models\DevelopmentStage $developmentStage
 * @property \open20\amos\partnershipprofiles\models\IntellectualProperty $intellectualProperty
 * @property \open20\amos\partnershipprofiles\models\PartnershipProfilesTypesMm[] $partnershipProfilesTypesMms
 * @property \open20\amos\partnershipprofiles\models\PartnershipProfilesCountriesMm[] $partnershipProfilesCountriesMms
 * @property \open20\amos\partnershipprofiles\models\PartnershipProfilesType[] $partnershipProfilesTypes
 * @property \open20\amos\comuni\models\IstatNazioni[] $partnershipProfileCountries
 * @property \open20\amos\admin\models\UserProfile $partnershipProfileFacilitator
 * @property \open20\amos\community\models\Community $community
 * @property \open20\amos\community\models\CommunityUserMm[] $communityUserMm
 *
 * @package open20\amos\partnershipprofiles\models\base
 */
abstract class PartnershipProfiles extends ContentModel implements CommunityInterface
{
    /**
     * @var array $attrPartnershipProfilesTypesMm Relation attribute for partnership profiles types
     */
    public $attrPartnershipProfilesTypesMm;

    /**
     * @var array $attrPartnershipProfilesCountriesMm Relation attribute for partnership profiles countries
     */
    public $attrPartnershipProfilesCountriesMm;

    /**
     * @var Module $partnershipProfilesModule
     */
    protected $partnershipProfilesModule = null;

    /**
     * @var Module $partnerProfModule
     */
    public $partnerProfModule = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partnership_profiles';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->partnerProfModule = Module::instance();

        parent::init();
        $communityConfigurationsId= null; 
        
        $this->partnershipProfilesModule = Module::instance();
        if($this->isNewRecord) {
            $module = $this->partnershipProfilesModule;
            if(Yii::$app instanceof \yii\web\Application)
            {
                $moduleCwh = \Yii::$app->getModule('cwh');
                if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                    $scope = $moduleCwh->getCwhScope();
                    if (isset($scope['community'])) {
                        $communityConfigurationsId='communityId-'.$scope['community'];
                    }
                }
            }
            if (($module &&isset($module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields']['expiration_in_months']) && $module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields']['expiration_in_months'] == false)||($module &&isset($module->fieldsConfigurations['fields']['expiration_in_months']) && $module->fieldsConfigurations['fields']['expiration_in_months'] == false)) {
                $this->expiration_in_months = 999;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $communityConfigurationsId= null;
        
        $module = \Yii::$app->getModule('partnershipprofiles');
        if(Yii::$app instanceof \yii\web\Application)
        {
            $moduleCwh = \Yii::$app->getModule('cwh');
            if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                $scope = $moduleCwh->getCwhScope();
                if (isset($scope['community'])) {
                    $communityConfigurationsId='communityId-'.$scope['community'];
                }
            }
        }
        $requiredFields = [
            'title',
            'short_description',
            'extended_description',
            'advantages_innovative_aspects',
            'expected_contribution',
            'partnership_profile_date',
            'expiration_in_months',
            'attrPartnershipProfilesTypesMm'
        ];
        if($module){
            if(!empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['required'])||!empty($module->fieldsConfigurations['required'])){
                $requiredFields = !empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['required']) ? $module->fieldsCommunityConfigurations[$communityConfigurationsId]['required'] : (!empty($module->fieldsConfigurations['required']) ? $module->fieldsConfigurations['required'] : []);
            }
        }
        $rules = ArrayHelper::merge(parent::rules(), [
            [$requiredFields, 'required'],
            [[
                'short_description',
                'extended_description',
                'advantages_innovative_aspects',
                'expected_contribution',
                'english_extended_description'
            ], 'string'],
            [[
                'status',
                'title',
                'other_prospect_desired_collab',
                'contact_person',
                'english_title',
                'other_work_language',
                'other_development_stage',
                'other_intellectual_property'
            ], 'string', 'max' => 255],
            [[
                'partnership_profile_date',
                'validated_at_least_once',
                'created_at',
                'updated_at',
                'deleted_at'
            ], 'safe'],
            [[
                'expiration_in_months',
                'willingness_foreign_partners'
            ], 'number'],
            [[
                'partnership_profile_facilitator_id',
                'work_language_id',
                'development_stage_id',
                'intellectual_property_id',
                'validated_at_least_once',
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [['partnership_profile_date'], 'date', 'format' => 'php:Y-m-d'],

            [['attrPartnershipProfilesTypesMm', 'attrPartnershipProfilesCountriesMm'], 'safe'],
        ]);

        // If there's no module or the module is present and the configuration is set to false enable the fields limits.
        if (
            is_null($this->partnershipProfilesModule) ||
            (!is_null($this->partnershipProfilesModule) && !$this->partnershipProfilesModule->disablePartProfLongStringFieldsLimits)
        ) {
            $rules[] = [[
                'short_description'
            ],  StringHtmlValidator::className(), 'max' => 400];
            $rules[] = [[
                'english_short_description'
            ],  StringHtmlValidator::className(), 'max' => 255];
            $rules[] = [[
                'extended_description'
            ],  StringHtmlValidator::className(), 'min' => 1000];
            $rules[] = [[
                'advantages_innovative_aspects'
            ],  StringHtmlValidator::className(), 'min' => 500];
            $rules[] = [[
                'expected_contribution'
            ],  StringHtmlValidator::className(), 'max' => 1000];
        } else {
            $rules[] = [[
                'english_short_description'
            ],  'string'];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $enablePartProfLongStringFieldsLimits = (
            is_null($this->partnershipProfilesModule) ||
            (!is_null($this->partnershipProfilesModule) && !$this->partnershipProfilesModule->disablePartProfLongStringFieldsLimits)
        );
        if ($enablePartProfLongStringFieldsLimits) {
            $shortDescLabel = Module::t('amospartnershipprofiles', 'Short description');
            $extDescLabel = Module::t('amospartnershipprofiles', 'Extended description');
            $advInnAspLabel = Module::t('amospartnershipprofiles', 'Advantages and Innovative Aspects');
        } else {
            $shortDescLabel = Module::t('amospartnershipprofiles', '#short_desc_without_char_limit');
            $extDescLabel = Module::t('amospartnershipprofiles', '#ext_desc_without_char_limit');
            $advInnAspLabel = Module::t('amospartnershipprofiles', '#adv_inn_asp_without_char_limit');
        }

        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amospartnershipprofiles', 'ID'),
            'status' => Module::t('amospartnershipprofiles', 'Status'),
            'title' => Module::t('amospartnershipprofiles', 'Title'),
            'short_description' => $shortDescLabel,
            'extended_description' => $extDescLabel,
            'advantages_innovative_aspects' => $advInnAspLabel,
            'other_prospect_desired_collab' => Module::t('amospartnershipprofiles', 'Other prospective / desired collaboration'),
            'expected_contribution' => Module::t('amospartnershipprofiles', 'Expected contribution'),
            'contact_person' => Module::t('amospartnershipprofiles', 'Contact person'),
            'partnership_profile_date' => Module::t('amospartnershipprofiles', 'Partnership Profile Date'),
            'expiration_in_months' => Module::t('amospartnershipprofiles', 'Expiration in months'),
            'english_title' => Module::t('amospartnershipprofiles', 'Title (english)'),
            'english_short_description' => Module::t('amospartnershipprofiles', 'Short description (english)'),
            'english_extended_description' => Module::t('amospartnershipprofiles', 'Extended description (english)'),
            'willingness_foreign_partners' => Module::t('amospartnershipprofiles', 'Are you willing to work with foreign partners?'),
            'other_work_language' => Module::t('amospartnershipprofiles', 'Other Work Language'),
            'other_development_stage' => Module::t('amospartnershipprofiles', 'Other Development Stage Expected Contributions'),
            'other_intellectual_property' => Module::t('amospartnershipprofiles', 'Other Intellectual Property On Contributes'),
            'validated_at_least_once' => Module::t('amospartnershipprofiles', 'Validated At Least Once'),
            'created_at' => Module::t('amospartnershipprofiles', 'Created at'),
            'updated_at' => Module::t('amospartnershipprofiles', 'Updated at'),
            'deleted_at' => Module::t('amospartnershipprofiles', 'Deleted at'),
            'created_by' => Module::t('amospartnershipprofiles', 'Created by'),
            'updated_by' => Module::t('amospartnershipprofiles', 'Updated by'),
            'deleted_by' => Module::t('amospartnershipprofiles', 'Deleted by'),

            'partnership_profile_facilitator_id' => Module::t('amospartnershipprofiles', 'Partnership Profile Facilitator ID'),
            'partnershipProfileFacilitator' => Module::t('amospartnershipprofiles', 'Partnership Profile Facilitator'),

            'partnershipProfilesType' => Module::t('amospartnershipprofiles', 'Partnership Profiles Type'),
            'attrPartnershipProfilesTypesMm' => Module::t('amospartnershipprofiles', 'Partnership Profiles Types'),

            'attrPartnershipProfilesCountriesMm' => Module::t('amospartnershipprofiles', 'Countries'),
            'partnership_profiles.partnership_profile_date' =>  Module::t('amospartnershipprofiles', 'Partnership Profiles Partnership Profile Date'),
            'partnership_profiles.updated_at' =>  Module::t('amospartnershipprofiles', 'Partnership Profiles Updated At'),
            'partnership_profiles.title' =>  Module::t('amospartnershipprofiles', 'Title'),

            'work_language_id' => Module::t('amospartnershipprofiles', 'Work Language ID'),
            'workLanguage' => Module::t('amospartnershipprofiles', 'Work Language'),

            'development_stage_id' => Module::t('amospartnershipprofiles', 'Development Stage ID'),
            'developmentStage' => Module::t('amospartnershipprofiles', 'Estimated Contribution Development Stage'),

            'intellectual_property_id' => Module::t('amospartnershipprofiles', 'Intellectual Property ID'),
            'intellectualProperty' => Module::t('amospartnershipprofiles', 'Intellectual Property on Expected Contributions'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkLanguage()
    {
        return $this->hasOne(\open20\amos\partnershipprofiles\models\WorkLanguage::className(), ['id' => 'work_language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevelopmentStage()
    {
        return $this->hasOne(\open20\amos\partnershipprofiles\models\DevelopmentStage::className(), ['id' => 'development_stage_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIntellectualProperty()
    {
        return $this->hasOne(\open20\amos\partnershipprofiles\models\IntellectualProperty::className(), ['id' => 'intellectual_property_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnershipProfilesTypesMms()
    {
        return $this->hasMany(\open20\amos\partnershipprofiles\models\PartnershipProfilesTypesMm::className(), ['partnership_profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnershipProfilesTypes()
    {
        return $this->hasMany(\open20\amos\partnershipprofiles\models\PartnershipProfilesType::className(), ['id' => 'partnership_profiles_type_id'])->via('partnershipProfilesTypesMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnershipProfilesCountriesMms()
    {
        return $this->hasMany(\open20\amos\partnershipprofiles\models\PartnershipProfilesCountriesMm::className(), ['partnership_profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnershipProfileCountries()
    {
        return $this->hasMany(\open20\amos\comuni\models\IstatNazioni::className(), ['id' => 'country_id'])->via('partnershipProfilesCountriesMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpressionsOfInterest()
    {
        return $this->hasMany($this->partnerProfModule->model('ExpressionsOfInterest'), ['partnership_profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDraftExpressionsOfInterest()
    {
        return $this->hasMany($this->partnerProfModule->model('ExpressionsOfInterest'), ['partnership_profile_id' => 'id'])
            ->andWhere([\open20\amos\partnershipprofiles\models\ExpressionsOfInterest::tableName() . '.status' => \open20\amos\partnershipprofiles\models\ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveExpressionsOfInterest()
    {
        return $this->hasMany($this->partnerProfModule->model('ExpressionsOfInterest'), ['partnership_profile_id' => 'id'])
            ->andWhere([\open20\amos\partnershipprofiles\models\ExpressionsOfInterest::tableName() . '.status' => \open20\amos\partnershipprofiles\models\ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_ACTIVE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToValidateExpressionsOfInterest()
    {
        return $this->hasMany($this->partnerProfModule->model('ExpressionsOfInterest'), ['partnership_profile_id' => 'id'])
            ->andWhere([\open20\amos\partnershipprofiles\models\ExpressionsOfInterest::tableName() . '.status' => \open20\amos\partnershipprofiles\models\ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelevantExpressionsOfInterest()
    {
        return $this->hasMany($this->partnerProfModule->model('ExpressionsOfInterest'), ['partnership_profile_id' => 'id'])
            ->andWhere([\open20\amos\partnershipprofiles\models\ExpressionsOfInterest::tableName() . '.status' => \open20\amos\partnershipprofiles\models\ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRejectedExpressionsOfInterest()
    {
        return $this->hasMany($this->partnerProfModule->model('ExpressionsOfInterest'), ['partnership_profile_id' => 'id'])
            ->andWhere([\open20\amos\partnershipprofiles\models\ExpressionsOfInterest::tableName() . '.status' => \open20\amos\partnershipprofiles\models\ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_REJECTED]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotDraftExpressionsOfInterest()
    {
        return $this->hasMany($this->partnerProfModule->model('ExpressionsOfInterest'), ['partnership_profile_id' => 'id'])
            ->andWhere(['!=', \open20\amos\partnershipprofiles\models\ExpressionsOfInterest::tableName() . '.status', \open20\amos\partnershipprofiles\models\ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnershipProfileFacilitator()
    {
        return $this->hasOne(AmosAdmin::instance()->model('UserProfile'), ['user_id' => 'partnership_profile_facilitator_id']);
    }

    /**
     * @inheritdoc
     */
    public function getCommunityId()
    {
        return $this->community_id;
    }

    /**
     * @inheritdoc
     */
    public function setCommunityId($communityId)
    {
        $this->community_id = $communityId;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity()
    {
        return $this->hasOne(\open20\amos\community\models\Community::className(), ['id' => 'community_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityUserMm()
    {
        return $this->hasMany(\open20\amos\community\models\CommunityUserMm::className(), ['community_id' => 'community_id']);
    }

    /**
     * news related news
     */
    public function getPartnershipProfilesCategoryMms()
    {
        return $this->hasMany(\open20\amos\partnershipprofiles\models\PartnershipProfilesCategoryMm::className(), ['partnership_profiles_id' => 'id']);
    }

    public function getOtherPartnershipCategories()
    {
        return $this->hasMany(\open20\amos\partnershipprofiles\models\PartnershipProfilesCategory::className(), ['id' => 'partnership_profiles_category_id'])->via('partnershipProfilesCategoryMms');

    }
}
