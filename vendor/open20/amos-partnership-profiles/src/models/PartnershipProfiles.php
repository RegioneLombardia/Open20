<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\models
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\interfaces\OrganizationsModuleInterface;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\attachments\models\File;
use open20\amos\community\models\Community;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\FacilitatorInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\user\User;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\partnershipprofiles\i18n\grammar\PartnershipProfilesGrammar;
use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\utility\PartnershipProfilesUtility;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class PartnershipProfiles
 * This is the model class for table "partnership_profiles".
 *
 * @method \cornernote\workflow\manager\components\WorkflowDbSource getWorkflowSource()
 * @method bool hasWorkflowStatus()
 * @method NULL|\raoul2000\workflow\base\Status getWorkflowStatus()
 * @method \yii\db\ActiveQuery hasOneFile($attribute = 'file', $sort = 'id')
 * @method \yii\db\ActiveQuery hasMultipleFiles($attribute = 'file', $sort = 'id')
 * @method string|null getRegolaPubblicazione()
 * @method array getTargets()
 *
 * @property string $partnershipProfileTypesString
 * @property string $expiredDate
 * @property int $facilitatoreUserProfileId
 *
 * @package open20\amos\partnershipprofiles\models
 */
class PartnershipProfiles extends \open20\amos\partnershipprofiles\models\base\PartnershipProfiles implements ViewModelInterface, FacilitatorInterface
{
    const PARTNERSHIP_PROFILES_WORKFLOW = 'PartnershipProfilesWorkflow';
    const PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT = 'PartnershipProfilesWorkflow/DRAFT';
    const PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE = 'PartnershipProfilesWorkflow/TOVALIDATE';
    const PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED = 'PartnershipProfilesWorkflow/VALIDATED';
    const PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED = 'PartnershipProfilesWorkflow/FEEDBACKRECEIVED';
    const PARTNERSHIP_PROFILES_WORKFLOW_STATUS_ARCHIVED = 'PartnershipProfilesWorkflow/ARCHIVED';
    const PARTNERSHIP_PROFILES_WORKFLOW_STATUS_CLOSED = 'PartnershipProfilesWorkflow/CLOSED';

    const PARTNERSHIPPROFILESADMIN = 'partnershipprofilesadmin';
    const PARTNERSHIPPROFILES = 'partnershipprofiles';

    // Scenarios
    const SCENARIO_VIEW = 'scenario_view';

    /**
     * @var File[] $partnershipProfileAttachments
     */
    private $partnershipProfileAttachments;

    /**
     * @var array $allowedExpressionOfInterestStates
     */
    private $allowedExpressionOfInterestStates = [
        self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED,
        self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED
    ];

    public $otherCategories;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::PARTNERSHIP_PROFILES_WORKFLOW)->getInitialStatusId();
            $this->partnership_profile_date = date("Y-m-d");
        }
    }

    /**
     * Getter for $this->partnershipProfileAttachments;
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getPartnershipProfileAttachments()
    {
        if (empty($this->partnershipProfileAttachments)) {
            $query = $this->hasMultipleFiles('partnershipProfileAttachments');
            $query->multiple = false;
            $this->partnershipProfileAttachments = $query->one();
        }
        return $this->partnershipProfileAttachments;
    }

    /**
     * @param $partnershipProfileAttachments
     */
    public function setPartnershipProfileAttachments($partnershipProfileAttachments)
    {
        $this->partnershipProfileAttachments = $partnershipProfileAttachments;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['partnershipProfileAttachments'], 'file', 'maxFiles' => 0],
            [['partnership_profile_facilitator_id'], 'validateFacilitator'],
            [['otherCategories'], 'safe'],
        ]);
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @return bool
     */
    public function validateFacilitator($attribute, $params, $validator)
    {
        if (($this->status == self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT) || $this->isNewRecord) {
            return true;
        }

        $allOk = false;
        $cwhConfigContents = CwhConfigContents::findOne(['tablename' => PartnershipProfiles::tableName()]);
        $pubblicazione = CwhPubblicazioni::findOne(['content_id' => $this->id, 'cwh_config_contents_id' => $cwhConfigContents->id]);
        $cwhPubblicazioniCwhNodiValidatoriMms = $pubblicazione->cwhPubblicazioniCwhNodiValidatoriMms;

        foreach ($cwhPubblicazioniCwhNodiValidatoriMms as $cwhPubblicazioniCwhNodiValidatoriMm) {
            /** @var \open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm $cwhPubblicazioniCwhNodiValidatoriMm */
            $cwhConfig = $cwhPubblicazioniCwhNodiValidatoriMm->cwhConfig;

            /** @var AmosAdmin $adminModule */
            $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
            $organizationsModuleName = $adminModule->getOrganizationModuleName();
            $organizzazioniModule = \Yii::$app->getModule($organizationsModuleName);

            $classNames = [
                "common\models\User",
                User::className()
            ];
            if (!is_null($organizzazioniModule)) {
                /** @var OrganizationsModuleInterface $organizzazioniModule */
                $classNames[] = $organizzazioniModule->getOrganizationModelClass();
            }

            $communityModule = \Yii::$app->getModule('community');

            if (in_array($cwhConfig->classname, $classNames)) {
                $allPlatformFacilitatorIds = UserProfileUtility::getAllFacilitatorUserIds();
                if (in_array($this->partnership_profile_facilitator_id, $allPlatformFacilitatorIds)) {
                    $allOk = true;
                }
            } elseif (!is_null($communityModule)) {
                /** @var \open20\amos\community\AmosCommunity $communityModule */
                if ($cwhConfig->classname == \open20\amos\community\models\Community::className()) {
                    $community = \open20\amos\community\models\Community::findOne($cwhPubblicazioniCwhNodiValidatoriMm->cwh_network_id);
                    if (!is_null($community)) {
                        $communityManagers = $community->communityManagers;
                        foreach ($communityManagers as $communityManager) {
                            /** @var User $communityManager */
                            if ($communityManager->id == $this->partnershipProfileFacilitator->user_id) {
                                $allOk = true;
                            }
                        }
                    }
                }
            }
        }

        if (!$allOk) {
            $this->addError($attribute, Module::t('amospartnershipprofiles', 'Facilitator not valid'));
        }

        return $allOk;
    }

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'title'
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_VIEW => [
                'status'
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'workflow' => [
                'class' => SimpleWorkflowBehavior::className(),
                'defaultWorkflowId' => self::PARTNERSHIP_PROFILES_WORKFLOW,
                'propagateErrorsToModel' => true
            ],
            'workflowLog' => [
                'class' => WorkflowLogFunctionsBehavior::className()
            ],
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
            'NotifyBehavior' => [
                'class' => NotifyBehavior::className(),
                'conditions' => []
            ],
            'SeoContentBehavior' => [
                'class' => SeoContentBehavior::className(),
                'defaultOgType' => 'article',
                'schema' => 'NewsArticle',
                'titleAttribute' => 'title',
                'descriptionAttribute' => 'short_description'
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
                'partnershipProfileAttachments' => Module::t('amospartnershipprofiles', 'Attachments')
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->status == self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED) {
            $this->validated_at_least_once = 1;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return int
     */
    public function getFacilitatoreUserProfileId()
    {
        $partProfFacilitator = $this->partnershipProfileFacilitator;
        if (!is_null($partProfFacilitator)) {
            return $partProfFacilitator->id;
        }
        return 0;
    }

    public function setFacilitatoreUserProfileId($userProfileId)
    {
        /** @var UserProfile $userProfileModel */
        $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
        $facilitatorUserProfile = $userProfileModel::findOne(['id' => $userProfileId]);
        if (!is_null($facilitatorUserProfile)) {
            $this->partnership_profile_facilitator_id = $facilitatorUserProfile->user_id;
        }
    }

    /**
     * @return string
     */
    public function calculateExpiryDate()
    {
        $partnershipProfileDate = new \DateTime($this->partnership_profile_date);
        $expiryDateStr = '';
        if ($partnershipProfileDate !== false) {
            $expiryDate = $partnershipProfileDate->add(new \DateInterval('P' . $this->expiration_in_months . 'M'));
            if ($expiryDate !== false) {
                $expiryDateStr = $expiryDate->format('Y-m-d');
            }
        }
        return $expiryDateStr;
    }

    public function getExpressionsOfInterestStatesCounter()
    {
        $statesCount = [
            'notdraft' => $this->getNotDraftExpressionsOfInterest()->count(),
            'draft' => $this->getDraftExpressionsOfInterest()->count(),
            'active' => $this->getActiveExpressionsOfInterest()->count(),
            'tovalidate' => $this->getToValidateExpressionsOfInterest()->count(),
            'relevant' => $this->getRelevantExpressionsOfInterest()->count(),
            'rejected' => $this->getRejectedExpressionsOfInterest()->count()
        ];
        return $statesCount;
    }

    /**
     * This method verify if it's possible to make an expression of interest on this partnership profile.
     * @param int[]|null $allowedPartnershipProfileIds
     * @return bool
     */
    public function expressionOfInterestAllowed($allowedPartnershipProfileIds = null)
    {
        $user = \Yii::$app->getUser();
		$userProfileClass = \open20\amos\admin\AmosAdmin::instance()->createModel('UserProfile');
		$userProfile = $userProfileClass::findOne(['user_id' => $user->id]);
        $validatedUser = $userProfile->validato_almeno_una_volta;

        if (!$validatedUser || (!is_null($allowedPartnershipProfileIds) && !in_array($this->id, $allowedPartnershipProfileIds))) {
            return false;
        }
        $ok = (
            in_array($this->status, $this->allowedExpressionOfInterestStates) &&
            \Yii::$app->user->can('EXPRESSIONSOFINTEREST_CREATE', ['model' => $this]) &&
            ($this->created_by != \Yii::$app->user->getId()) &&
            ($this->partnership_profile_facilitator_id != \Yii::$app->user->getId())
            && !$this->isExpired()
        );
        foreach ($this->expressionsOfInterest as $expressionsOfInterest) {
            /** @var ExpressionsOfInterest $expressionsOfInterest */
            if ($expressionsOfInterest->created_by == \Yii::$app->user->getId()) {
                $ok = false;
            }
        }
        return $ok;
    }

    /**
     * This method checks if this partnership profile is expired.
     * It uses the calculated expiry date and the actual date to do the check.
     * @return bool
     */
    public function isExpired()
    {
        $dbDateFormat = 'Y-m-d';
        $expiryDateObj = PartnershipProfilesUtility::calcExpiryDate($this);
        $actualDateObj = \DateTime::createFromFormat($dbDateFormat, date($dbDateFormat));
        $expiryDateTimestamp = $expiryDateObj->getTimestamp();
        $actualDateTimestamp = $actualDateObj->getTimestamp();
        return ($expiryDateTimestamp < $actualDateTimestamp);
    }

    /**
     * This method return the expired date.
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getExpiredDate()
    {
        $expiryDateObj = PartnershipProfilesUtility::calcExpiryDate($this);
        if (!empty($expiryDateObj)) {
            return \Yii::$app->formatter->asDate($expiryDateObj->format('Y-m-d'));
        } else {
            return '';
        }
    }

    /**
     * This method return a string with all the partnership profile types concatenated with commas.
     * Useful in grid view or similar places.
     * @return string
     */
    public function getPartnershipProfileTypesString()
    {
        $partnershipProfileTypesString = '';
        foreach ($this->partnershipProfilesTypes as $partnershipProfilesType) {
            /** @var PartnershipProfilesType $partnershipProfilesType */
            if (strlen($partnershipProfileTypesString) > 0) {
                $partnershipProfileTypesString .= ', ';
            }
            $partnershipProfileTypesString .=  $partnershipProfilesType->name;
        }
        return $partnershipProfileTypesString;
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
            'title',
            [
                'label' => $this->getAttributeLabel('status'),
                'value' => function ($model) {
                    /** @var PartnershipProfiles $model */
                    return $model->getWorkflowStatus()->getLabel();
                }
            ],
            [
                'attribute' => 'short_description',
                'format' => 'html',
                'label' => Module::t('amospartnershipprofiles', '#short_description')
            ],
            'partnership_profile_date:date',
            [
                'label' => Module::t('amospartnershipprofiles', 'Expiry date'),
                'value' => function ($model) {
                    /** @var PartnershipProfiles $model */
                    return $model->expiredDate;
                }
            ],
            [
                'label' => Module::t('amospartnershipprofiles', 'Partnership Profiles Types'),
                'value' => function ($model) {
                    /** @var PartnershipProfiles $model */
                    return $model->getPartnershipProfileTypesString();
                }
            ],
            [
                'attribute' => 'createdUserProfile',
                'label' => Module::t('amospartnershipprofiles', 'Partnership profile creator')
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return "partnershipprofiles/partnership-profiles/view";
    }

    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        return $this->short_description;
    }

    /**
     * @inheritdoc
     */
    public function getPublicatedFrom()
    {
        return $this->partnership_profile_date;
    }

    /**
     * Return the publicated from property label
     * @return string
     */
    public function getPublicatedFromLabel()
    {
        return $this->getAttributeLabel('partnership_profile_date');
    }

    /**
     * @inheritdoc
     */
    public function getPublicatedAt()
    {
        return $this->calculateExpiryDate();
    }

    /**
     * Return the publicated at property label
     * @return string
     */
    public function getPublicatedAtLabel()
    {
        return Module::t('amospartnershipprofiles', 'Expiry date');
    }

    /**
     * @inheritdoc
     */
    public function getCategory()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getPluginWidgetClassname()
    {
        return WidgetIconPartnershipProfilesDashboard::className();
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT;
    }

    /**
     * Return the translated status label
     * @return string
     */
    public function getTranslatedStatus()
    {
        return Module::t('amospartnershipprofiles', $this->getWorkflowStatus()->getLabel());
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'PARTNERSHIP_PROFILES_VALIDATOR';
    }

    /**
     * @inheritdoc
     */
    public function getFacilitatorRole()
    {
        return 'PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR';
    }

    /**
     * @return PartnershipProfilesGrammar
     */
    public function getGrammar()
    {
        return new PartnershipProfilesGrammar();
    }

    /**
     * @return array list of statuses that for cwh is validated
     */
    public function getCwhValidationStatuses()
    {
        return [self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED, self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED];
    }

    /**
     * The community created by the context model (community related to project-management, events or a community itself)
     * @return Community
     */
    public function getCommunityModel()
    {
        return $this->community;
    }

    /**
     * For this entity the validation logic is different from open20\amos\core\record\Record
     * The facilitator is linked to this "partnership profile"
     * @return array
     */
    public function getValidatorUsersId()
    {
        $facilitators = [];
        if ($this->partnership_profile_facilitator_id) {
            $facilitators[] = $this->partnership_profile_facilitator_id;
        }
        return $facilitators;
    }


    /**
     * @return array
     */
    public function getStatusToRenderToHide()
    {
        $statusToRender     = [
            self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT => Module::t('amospartnershipprofiles', 'Modifica in corso'),
        ];
        $isCommunityManager = false;
        if (\Yii::$app->getModule('community')) {
            $isCommunityManager = \open20\amos\community\utilities\CommunityUtil::isLoggedCommunityManager();
            if ($isCommunityManager) {
                $isCommunityManager = true;
            }
        }
        // if you are a community manager a validator/facilitator or ADMIN you Can publish directly
        if (\Yii::$app->user->can('PartnershipProfilesValidate', ['model' => $this]) || \Yii::$app->user->can('ADMIN') || $isCommunityManager) {
            $statusToRender  = ArrayHelper::merge($statusToRender,
                [self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED => Module::t('amospartnershipprofiles', 'Pubblicata')]);
            $hideDraftStatus = [];
        } else {
            $statusToRender    = ArrayHelper::merge($statusToRender,
                [
                    self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE => Module::t('amospartnershipprofiles', 'Richiedi pubblicazione'),
                ]);
            $hideDraftStatus[] = self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED;
        }
        return ['statusToRender' => $statusToRender, 'hideDraftStatus' => $hideDraftStatus];
    }

    /**
     *
     */
    public function saveOtherCategories(){
        $module = \Yii::$app->getModule('partnershipprofiles');
        if($module = $module->enableCategories) {
            $otherCategories = $this->otherCategories;
            PartnershipProfilesCategoryMm::deleteAll(['partnership_profiles_id' => $this->id]);
            foreach ($otherCategories as $category_id) {
                $categoryMm = new PartnershipProfilesCategoryMm();
                $categoryMm->partnership_profiles_category_id = $category_id;
                $categoryMm->partnership_profiles_id = $this->id;
                $categoryMm->save(false);
            }
        }
    }

    /**
     *
     */
    public function loadOtherCategories(){
        $otherCategories = $this->otherPartnershipCategories;
        $this->otherCategories = $otherCategories;

    }
}
