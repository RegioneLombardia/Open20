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

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\comments\models\CommentInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\partnershipprofiles\events\ExpressionsOfInterestWorkflowEvent;
use open20\amos\partnershipprofiles\i18n\grammar\ExpressionsOfInterestGrammar;
use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\utility\ExpressionsOfInterestUtility;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class ExpressionsOfInterest
 * This is the model class for table "expressions_of_interest".
 *
 * @method \cornernote\workflow\manager\components\WorkflowDbSource getWorkflowSource()
 * @method bool hasWorkflowStatus()
 * @method NULL|\raoul2000\workflow\base\Status getWorkflowStatus()
 * @method \yii\db\ActiveQuery hasOneFile($attribute = 'file', $sort = 'id')
 * @method \yii\db\ActiveQuery hasMultipleFiles($attribute = 'file', $sort = 'id')
 * @method string|null getRegolaPubblicazione()
 * @method array getTargets()
 *
 * @package open20\amos\partnershipprofiles\models
 */
class ExpressionsOfInterest extends \open20\amos\partnershipprofiles\models\base\ExpressionsOfInterest implements ViewModelInterface, CommentInterface, ModelLabelsInterface
{
    const EXPRESSIONS_OF_INTEREST_WORKFLOW = 'ExpressionsOfInterestWorkflow';
    const EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT = 'ExpressionsOfInterestWorkflow/DRAFT';
    const EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_ACTIVE = 'ExpressionsOfInterestWorkflow/ACTIVE';
    const EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE = 'ExpressionsOfInterestWorkflow/TOVALIDATE';
    const EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT = 'ExpressionsOfInterestWorkflow/RELEVANT';
    const EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_REJECTED = 'ExpressionsOfInterestWorkflow/REJECTED';

    const EXPRESSIONSOFINTEREST = 'expressionsofinterest';

    // Scenarios
    const SCENARIO_VIEW = 'scenario_view';

    /**
     * @var string $user_network_reference
     */
    public $user_network_reference;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::EXPRESSIONS_OF_INTEREST_WORKFLOW)->getInitialStatusId();
        }

        $this->on('afterChangeStatusFrom{' . self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT . '}to{' . self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_ACTIVE . '}', [new ExpressionsOfInterestWorkflowEvent(), 'sendConfirmToPartnershipProfileCreator'], $this);
        $this->on('afterEnterStatus{' . self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE . '}', [new ExpressionsOfInterestWorkflowEvent(), 'sendNotifyToExpressionsOfInterestCreator'], $this);
        $this->on('afterEnterStatus{' . self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT . '}', [new ExpressionsOfInterestWorkflowEvent(), 'sendNotifyToExpressionsOfInterestCreator'], $this);
        $this->on('afterEnterStatus{' . self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_REJECTED . '}', [new ExpressionsOfInterestWorkflowEvent(), 'sendNotifyToExpressionsOfInterestCreator'], $this);
    }

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'partnership_offered'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $modulePartenershipProfiles = \Yii::$app->getModule('partnershipprofiles');
        $communityOfReferenceRequired = true;

        if($modulePartenershipProfiles) {
            $communityOfReferenceRequired = $modulePartenershipProfiles->communityOfReferenceRequired;
        }
        $rules = ArrayHelper::merge(parent::rules(), [
            [['user_network_reference'], 'string', 'max' => 255],
            [['user_network_reference'], 'safe'],
        ]);
        if (!$this->isNewRecord && $communityOfReferenceRequired && (count(ExpressionsOfInterestUtility::getReferenceCommunityOrOrganizationList()) > 0)) {
            $rules[] = [[
                'user_network_reference_classname',
                'user_network_reference_id',
                'user_network_reference'
            ], 'required'];
        }
        return $rules;
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
                'defaultWorkflowId' => self::EXPRESSIONS_OF_INTEREST_WORKFLOW,
                'propagateErrorsToModel' => true
            ],
            'workflowLog' => [
                'class' => WorkflowLogFunctionsBehavior::className()
            ],
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'user_network_reference' => Module::t('amospartnershipprofiles', 'Reference Community / Organization'),
            'userNetworkReference' => Module::t('amospartnershipprofiles', 'Reference Community / Organization'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
            'partnership_offered:html',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var ExpressionsOfInterest $model */
                    return $model->hasWorkflowStatus() ? Module::t('amospartnershipprofiles', $model->getWorkflowStatus()->getLabel()) : '--';
                }
            ],
            [
                'label' => Module::t('amospartnershipprofiles', 'Partnership Profile'),
                'attribute' => 'partnershipProfile.title'
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return "partnershipprofiles/expressions-of-interest/view";
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
    public function isCommentable()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function sendNotification()
    {
        return false;
    }

    /**
     * @return ExpressionsOfInterestGrammar
     */
    public function getGrammar()
    {
        return new ExpressionsOfInterestGrammar();
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->partnership_offered;
    }

    /**
     * @inheritdoc
     */
    public function getWorkflowStatusLabel()
    {
        $status = parent::getWorkflowStatusLabel();
        return ((strlen($status) > 0) ? Module::t('amospartnershipprofiles', $status) : '-');
    }


    /**
     * @return array
     */
    public function getStatusToRenderToHide()
    {
        $statusToRender     = [
            self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT => Module::t('amospartnershipprofiles', 'Modifica in corso'),
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
                [self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT => Module::t('amospartnershipprofiles', 'Pubblicata')]);
            $hideDraftStatus = [];
        } else {
            $statusToRender    = ArrayHelper::merge($statusToRender,
                [
                    self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE => Module::t('amospartnershipprofiles', 'Richiedi pubblicazione'),
                ]);
            $hideDraftStatus[] = self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT;
        }
        return ['statusToRender' => $statusToRender, 'hideDraftStatus' => $hideDraftStatus];
    }
}
