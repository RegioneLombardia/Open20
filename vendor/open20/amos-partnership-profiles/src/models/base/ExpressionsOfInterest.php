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

use open20\amos\core\record\AmosRecordAudit;
use open20\amos\partnershipprofiles\Module;
use yii\helpers\ArrayHelper;

/**
 * Class ExpressionsOfInterest
 *
 * This is the base-model class for table "expressions_of_interest".
 *
 * @property integer $id
 * @property string $status
 * @property string $partnership_offered
 * @property string $additional_information
 * @property string $clarifications
 * @property integer $partnership_profile_id
 * @property string $user_network_reference_classname
 * @property integer $user_network_reference_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\partnershipprofiles\models\PartnershipProfiles $partnershipProfile
 *
 * @package open20\amos\partnershipprofiles\models\base
 */
class ExpressionsOfInterest extends AmosRecordAudit
{
    /**
     * @var Module $partnerProfModule
     */
    public $partnerProfModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->partnerProfModule = Module::instance();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expressions_of_interest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [[
                'status',
                'partnership_offered',
                'additional_information',
                'partnership_profile_id'
            ], 'required'],
            [[
                'partnership_offered',
                'additional_information',
                'clarifications',
                'user_network_reference_classname'
            ], 'string'],
            [[
                'partnership_profile_id',
                'user_network_reference_id',
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [[
                'created_at',
                'updated_at',
                'deleted_at'
            ], 'safe'],
            [['status'], 'string', 'max' => 255],
            [['partnership_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->partnerProfModule->model('PartnershipProfiles'), 'targetAttribute' => ['partnership_profile_id' => 'id']],
        ];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amospartnershipprofiles', 'ID'),
            'status' => Module::t('amospartnershipprofiles', 'Status'),
            'partnership_offered' => Module::t('amospartnershipprofiles', 'Partnership offered / Prospected collaboration'),
            'additional_information' => Module::t('amospartnershipprofiles', 'Additional information on who expresses interest'),
            'clarifications' => Module::t('amospartnershipprofiles', 'Requests and / or clarifications regarding the partnership profile'),
            'partnership_profile_id' => Module::t('amospartnershipprofiles', 'Partnership Profile ID'),
            'user_network_reference_id' => Module::t('amospartnershipprofiles', 'User Network Reference ID'),
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
        return $this->hasOne($this->partnerProfModule->model('PartnershipProfiles'), ['id' => 'partnership_profile_id']);
    }
}
