<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\models
 * @category   CategoryName
 */

namespace open20\amos\community\models;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\community\AmosCommunity;
use open20\amos\community\i18n\grammar\CommunityGrammar;
use open20\amos\community\models\search\CommunitySearch;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\community\widgets\icons\WidgetIconCommunityDashboard;
use open20\amos\community\widgets\UserNetworkWidget;
use open20\amos\core\user\User;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\behaviors\TaggableInterestingBehavior;
use open20\amos\cwh\models\CwhAuthAssignment;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;


/**
 * Class Community
 * This is the model class for table "community".
 *
 * @property \open20\amos\community\models\CommunityUserMm[] $communityManagerMms
 * @property \open20\amos\core\user\User[] $communityManagers
 *
 * @package open20\amos\community\models
 */
class Community extends \open20\amos\community\models\base\Community implements CommunityContextInterface
{

    /**
     * @var    string    COMMUNITY_WORKFLOW    Community Workflow ID
     */
    const COMMUNITY_WORKFLOW = 'CommunityWorkflow';

    /**
     * @var    string    COMMUNITY_WORKFLOW_STATUS_DRAFT        ID status draft in the model workflow (editing in progress)
     */
    const COMMUNITY_WORKFLOW_STATUS_DRAFT = 'CommunityWorkflow/DRAFT';

    /**
     * @var    string    COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE        ID status to be validated in the model workflow
     */
    const COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE = 'CommunityWorkflow/TOVALIDATE';

    /**
     * @var    string    COMMUNITY_WORKFLOW_STATUS_VALIDATED        ID status validated in the model workflow
     */
    const COMMUNITY_WORKFLOW_STATUS_VALIDATED = 'CommunityWorkflow/VALIDATED';

    /**
     * @var    string    COMMUNITY_WORKFLOW_STATUS_NOT_VALIDATED        ID status not validated in the model workflow
     */
    const COMMUNITY_WORKFLOW_STATUS_NOT_VALIDATED = 'CommunityWorkflow/NOTVALIDATED';

    /** @var string */
    const ROLE_COMMUNITY_MANAGER = 'COMMUNITY_MANAGER';

    /**
     * @var    string    COMMUNITY_DEFAULT_NETWORK_ID        ID default newtwork configuration id = 3 for community
     */
    const COMMUNITY_DEFAULT_NETWORK_ID = 3;

    /**
     * @var mixed $communityLogo Community logo.
     */
    //public $communityLogo;

    /**
     * @var mixed $communityCoverImage Community cover image.
     */
    public $communityCoverImage;

    /**
     * @var bool $backToEdit - used in Community form
     * if true, name or description have been modified and community is in published status and community goes back to edit status
     */
    public $backToEdit;
    public $level = 0;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //in creation phase the status is set to initial status defined in community workflow
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::COMMUNITY_WORKFLOW)->getInitialStatusId();
            if ($this->status == self::COMMUNITY_WORKFLOW_STATUS_VALIDATED) {
                $this->validated_once = 1;
            }
            if (!is_null($this->communityModule->communityType)) {
                $this->community_type_id = $this->communityModule->communityType;
            }
            if($this->hasAttribute('force_workflow')){
                $this->force_workflow = ($this->communityModule->forceWorkflow($this) === true) ? 1 : 0;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        //$this->communityLogo = $this->getCommunityLogo()->one();
        $this->communityCoverImage = $this->getCommunityCoverImage()->one();
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        CommunityUtil::autoAddCommunityManagersToCommunity($this, CommunityUserMm::STATUS_ACTIVE);
    }
    
    /**
     * This method checks if a community is deleted.
     * @return bool
     */
    public function isDeleted()
    {
        return (!is_null($this->deleted_at));
    }
    
    /**
     * Getter for $this->communityLogo;
     * @return \yii\db\ActiveQuery
     */
    /* public function getCommunityLogo()
     {
         return $this->communityLogo = $this->hasOneFile('communityLogo')->one();
     }*/

    public function getModelImage()
    {
        return $this->modelImage = $this->hasOneFile('communityLogo')->one();
    }

    /**
     * Getter for $this->communityCoverImage;
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityCoverImage()
    {
        return $this->hasOneFile('communityCoverImage');
    }

    /**
     * Url of community avatar img (logo)
     *
     * @param string $dimension Size of the image. Default = small.
     * @param bool $absolute - If the full link to the image is needed (eg. to render images in a email) or the relative url (default= false - relative url)
     * @return string $url
     */
    public function getAvatarUrl($dimension = 'small', $absolute = false, $getPublicUrl = false)
    {
        $dimensionsOldVsNew = [
            'small' => 'square_small',
            'medium' => 'square_medium',
            'large' => 'square_large',
        ];
        if (isset($dimensionsOldVsNew[$dimension])) {
            $dimension = $dimensionsOldVsNew[$dimension];
        }

        if (!is_null($this->communityLogo)) {
            if ($getPublicUrl) {
                $url = $this->communityLogo->getWebUrl($dimension, $absolute, true);
            } else {
                $url = $this->communityLogo->getUrl($dimension, $absolute, true);
            }
        } else {
            $url = \Yii::$app->getUrlManager()->createAbsoluteUrl(Url::to('/img/img_default.jpg'));
        }
        return $url;
    }

    /**
     * Absolute Url of community avatar img (logo)
     *
     * @param string $dimension Size of the image. Default = small.
     * @return string $url
     */
    public function getAvatarWebUrl($dimension = 'small')
    {
        return $this->getAvatarUrl($dimension, true);
    }

    /**
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['communityLogo'], 'file', 'extensions' => 'jpeg, jpg, png, gif', 'minSize' => 1],
            [['communityCoverImage'], 'file', 'extensions' => 'jpeg, jpg, png, gif', 'minSize' => 1],
            [['backToEdit'], 'integer'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'communityLogo' => AmosCommunity::t('amoscommunity', 'Logo')
        ]);
    }

    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
            'name',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getBaseRole()
    {
        $baseRole = CommunityUserMm::ROLE_PARTICIPANT;
        $moduleCommunity = Yii::$app->getModule('community');
        if ($moduleCommunity->extendRoles) {
            $baseRole = CommunityUserMm::ROLE_READER;
        }
        return $baseRole;
    }

    /**
     * @inheritdoc
     */
    public function getRolePermissions($role)
    {
        switch ($role) {
            case CommunityUserMm::ROLE_READER:
                return [];
                break;
            case CommunityUserMm::ROLE_AUTHOR:
                return ['CWH_PERMISSION_VALIDATE'];
                break;
            case CommunityUserMm::ROLE_EDITOR:
                return ['CWH_PERMISSION_CREATE', 'CWH_PERMISSION_VALIDATE'];
                break;
            case CommunityUserMm::ROLE_PARTICIPANT:
                return ['CWH_PERMISSION_CREATE'];
                break;
            case CommunityUserMm::ROLE_COMMUNITY_MANAGER:
                return ['CWH_PERMISSION_CREATE', 'CWH_PERMISSION_VALIDATE'];
                break;
            default:
                return ['CWH_PERMISSION_CREATE'];
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function getCommunityModel()
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getNextRole($role)
    {
        switch ($role) {
            case CommunityUserMm::ROLE_PARTICIPANT:
                return CommunityUserMm::ROLE_COMMUNITY_MANAGER;
                break;
            case CommunityUserMm::ROLE_COMMUNITY_MANAGER:
                return CommunityUserMm::ROLE_PARTICIPANT;
                break;
            default:
                return CommunityUserMm::ROLE_PARTICIPANT;
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'workflow' => [
                'class' => SimpleWorkflowBehavior::className(),
                'defaultWorkflowId' => self::COMMUNITY_WORKFLOW,
                'propagateErrorsToModel' => true,
            ],
            'NotifyBehavior' => [
                'class' => NotifyBehavior::className(),
                'conditions' => [],
            ],
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
            'WorkflowLogFunctionsBehavior' => [
                'class' => WorkflowLogFunctionsBehavior::className(),
            ],
            'SeoContentBehavior' => [
                'class' => SeoContentBehavior::className(),
                'titleAttribute' => 'name',
                'descriptionAttribute' => 'description',
                'imageAttribute' => 'communityLogo',
                'defaultOgType' => 'article',
                'schema' => 'NewsArticle'
            ]
        ]);

        $cwhModule = Yii::$app->getModule('cwh');
        $tagModule = Yii::$app->getModule('tag');
        if (isset($cwhModule) && isset($tagModule)) {
            $cwhTaggable = [
                'interestingTaggable' => [
                    'class' => TaggableInterestingBehavior::className(),
                    'tagValueAttribute' => 'id',
                    'tagValuesSeparatorAttribute' => ',',
                    'tagValueNameAttribute' => 'nome',
                ]
            ];

            $behaviors = ArrayHelper::merge($behaviors, $cwhTaggable);
        }

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function getContextRoles()
    {
        $roles = [
            CommunityUserMm::ROLE_PARTICIPANT,
            CommunityUserMm::ROLE_COMMUNITY_MANAGER
        ];
        $moduleCommunity = Yii::$app->getModule('community');
        if ($moduleCommunity->extendRoles) {
            //add additional roles
            $roles = [
                CommunityUserMm::ROLE_READER,
                CommunityUserMm::ROLE_AUTHOR,
                CommunityUserMm::ROLE_EDITOR,
                CommunityUserMm::ROLE_COMMUNITY_MANAGER
            ];
        }
        return $roles;
    }

    /**
     *
     * @return string
     */
    public function getManagerRole()
    {
        $roleName = CommunityUserMm::ROLE_COMMUNITY_MANAGER;
        if (!empty($this->context) && strcmp($this->context, Community::className())) {
            /** @var CommunityContextInterface $obj */
            $obj = new $this->context;
            $roleName = $obj->getManagerRole();
        }
        return $roleName;
    }

    /**
     * @inheritdoc
     */
    public function getPluginModule()
    {
        return 'community';
    }

    /**
     * @inheritdoc
     */
    public function getPluginController()
    {
        return 'community';
    }

    /**
     * @inheritdoc
     */
    public function getRedirectAction()
    {
        return 'update';
    }

    public function getAdditionalAssociationTargetQuery($communityId)
    {
        $communityUserMms = CommunityUserMm::find()->andWhere(['community_id' => $communityId]);
        return User::find()->andFilterWhere(['not in', 'id', $communityUserMms->select('user_id')]);
    }

    /**
     * Add CWH permissions based on the role for which a permissions array has been specified,
     * Remove CWH permissions on community domain in case of role degradation
     * or delete all permission in case of user-community association deletion
     *
     * @param CommunityUserMm $communityUserMmRow
     * @param bool|false $delete - if true remove all permission (case deletion user-community association)
     */
    public function setCwhAuthAssignments($communityUserMmRow, $delete = false)
    {
        /** @var AmosCwh $cwhModule */
        $cwhModule = Yii::$app->getModule("cwh");
        $cwhNodeId = 'community-' . $this->id;
        $userId = $communityUserMmRow->user_id;
        $cwhConfigId = self::getCwhConfigId();
        $cwhPermissions = CwhAuthAssignment::find()->andWhere([
            'user_id' => $userId,
            'cwh_config_id' => $cwhConfigId,
            'cwh_network_id' => $this->id
        ])->all();

        if ($delete) {
            if (!empty($cwhPermissions)) {
                /** @var CwhAuthAssignment $cwhPermission */
                foreach ($cwhPermissions as $cwhPermission) {
                    $cwhPermission->delete();
                }
            }
        } else {
            $existingPermissions = [];
            foreach ($cwhPermissions as $item) {
                $existingPermissions[$item->item_name] = $item;
            }

            /** @var Community $callingModel */
            $callingModel = Yii::createObject($this->context);
            /** @var array $rolePermissions */
            $rolePermissions = $callingModel->getRolePermissions($communityUserMmRow->role);
            $permissionsToAdd = [];
            if (!is_null($rolePermissions) && count($rolePermissions)) {
                // for each enabled Content model in Cwh
                foreach ($cwhModule->modelsEnabled as $modelClassname) {
                    foreach ($rolePermissions as $permission) {
                        $cwhAuthAssignment = new CwhAuthAssignment();
                        $cwhAuthAssignment->user_id = $userId;
                        $cwhAuthAssignment->item_name = $permission . '_' . $modelClassname;
                        $cwhAuthAssignment->cwh_nodi_id = $cwhNodeId;
                        $cwhAuthAssignment->cwh_config_id = $cwhConfigId;
                        $cwhAuthAssignment->cwh_network_id = $this->id;
                        $permissionsToAdd[$cwhAuthAssignment->item_name] = $cwhAuthAssignment;
                    }
                }
            }
            // if role is CM add permissions of creation/validation of subcommunities of current community (N.B. only for community not created by events/projects)
            if ($this->context == self::className() && $communityUserMmRow->role == CommunityUserMm::ROLE_COMMUNITY_MANAGER) {
                $cwhCreateSubCommunities = new CwhAuthAssignment();
                $cwhCreateSubCommunities->user_id = $userId;
                $cwhCreateSubCommunities->item_name = $cwhModule->permissionPrefix . "_CREATE_" . self::className();
                $cwhCreateSubCommunities->cwh_nodi_id = $cwhNodeId;
                $cwhCreateSubCommunities->cwh_config_id = $cwhConfigId;
                $cwhCreateSubCommunities->cwh_network_id = $this->id;
                $permissionsToAdd[$cwhCreateSubCommunities->item_name] = $cwhCreateSubCommunities;
                $cwhValidateSubCommunities = new CwhAuthAssignment();
                $cwhValidateSubCommunities->user_id = $userId;
                $cwhValidateSubCommunities->item_name = $cwhModule->permissionPrefix . "_VALIDATE_" . self::className();
                $cwhValidateSubCommunities->cwh_nodi_id = $cwhNodeId;
                $cwhValidateSubCommunities->cwh_config_id = $cwhConfigId;
                $cwhValidateSubCommunities->cwh_network_id = $this->id;
                $permissionsToAdd[$cwhValidateSubCommunities->item_name] = $cwhValidateSubCommunities;
            }
            if (!empty($permissionsToAdd)) {
                /** @var CwhAuthAssignment $permissionToAdd */
                foreach ($permissionsToAdd as $key => $permissionToAdd) {
                    //if user has not already the permission for the community , add it to cwh auth assignment
                    if (!array_key_exists($key, $existingPermissions)) {
                        $permissionToAdd->save(false);
                    }
                }
            }
            // check if there are permissions to remove
            if (!empty($existingPermissions)) {
                /** @var CwhAuthAssignment $cwhPermission */
                foreach ($existingPermissions as $key => $cwhPermission) {
                    if (!array_key_exists($key, $permissionsToAdd)) {
                        $cwhPermission->delete();
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getUserId()
    {
        return Yii::$app->getUser()->id;
    }

    /**
     * @inheritdoc
     */
    public function getMmTableName()
    {
        return CommunityUserMm::tableName();
    }

    /**
     * @inheritdoc
     */
    public function getMmNetworkIdFieldName()
    {
        return 'community_id';
    }

    /**
     * @inheritdoc
     */
    public function getMmUserIdFieldName()
    {
        return 'user_id';
    }

    public function getMmClassName()
    {
        return CommunityUserMm::className();
    }

    /**
     * @inheritdoc
     */
    public function isNetworkUser($networkId = null, $userId = null, $onlyActiveStatus = false)
    {
        if (!isset($networkId)) {
            $networkId = $this->id;
        }
        if (!isset($userId)) {
            $userId = $this->getUserId();
        }
        $mmRow = CommunityUserMm::findOne([
            $this->getMmNetworkIdFieldName() => $networkId,
            $this->getMmUserIdFieldName() => $userId,
            'status' => CommunityUserMm::STATUS_ACTIVE
        ]);
        if (!is_null($mmRow)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isValidated($networkId = null)
    {
        if (!isset($networkId)) {
            $community = $this;
        } else {
            $community = Community::findOne($networkId);
        }
        if (!isset($community) || $community->isNewRecord) {
            return false;
        }
        if ($community->status == self::COMMUNITY_WORKFLOW_STATUS_VALIDATED || ($community->validated_once && $community->visible_on_edit && $community->status == self::COMMUNITY_WORKFLOW_STATUS_DRAFT)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return self::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::COMMUNITY_WORKFLOW_STATUS_VALIDATED;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::COMMUNITY_WORKFLOW_STATUS_DRAFT;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'COMMUNITY_VALIDATOR';
    }

    /**
     * @param int|null $userId - if null the logged user id is considered
     * @return bool
     */
    public function isCommunityManager($userId = null)
    {
        if (!isset($userId)) {
            $userId = \Yii::$app->getUser()->id;
        }
        $managerRole = $this->getManagerRole();
        $communityMm = CommunityUserMm::findOne([
            'community_id' => $this->id,
            'role' => $managerRole,
            'status' => CommunityUserMm::STATUS_ACTIVE,
            'user_id' => $userId
        ]);
        return !is_null($communityMm);
    }

    /**
     * @param int|null $userId - if null the logged user id is considered
     * @return bool
     */
    public function hasRole($userId, $role)
    {
        if (!isset($userId)) {
            $userId = \Yii::$app->getUser()->id;
        }
        $communityMm = CommunityUserMm::findOne([
            'community_id' => $this->id,
            'role' => $role,
            'status' => CommunityUserMm::STATUS_ACTIVE,
            'user_id' => $userId
        ]);
        return !is_null($communityMm);
    }

    /**
     * @param null $userId
     * @param bool $isUpdate
     * @return string
     */
    public function getUserNetworkWidget($userId = null, $isUpdate = false)
    {
        if (is_null(Yii::$app->getModule(AmosCommunity::getModuleName()))) {
            return '';
        }
        return UserNetworkWidget::widget(['userId' => $userId, 'isUpdate' => $isUpdate]);
    }

    public static function getVisibility()
    {
        return "0";
    }

    public function getUserNetworkAssociationQuery($userId = null, $params = [], $onlyActiveStatus = false)
    {
        if (empty($userId)) {
            $userId = Yii::$app->user->id;
        }

        $communitySearch = new CommunitySearch();
        /** @var ActiveQuery $query */
        $query = $communitySearch->buildQuery($params, 'all', $onlyActiveStatus, $userId);

        /** @var ActiveQuery $queryJoined */
        $queryJoined = $this->getUserNetworkQuery($userId, $params, false)->select(static::tableName() . '.id')->column();
        if (!empty($queryJoined)) {
            $query->andWhere(['not in', static::tableName() . '.id', $queryJoined]);
        }
        $query->andWhere(static::tableName() . '.deleted_at is null');

        return $query;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->__shortText($this->description, 100);
    }

    /**
     * @return string
     */
    public function getDescription($truncate)
    {
        $ret = $this->description;

        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
        }
        return $ret;
    }


    /**
     * @return string The url to view a single model
     */
    public function getViewUrl()
    {
        return "/community/community/view";
    }

    /**
     * @return mixed
     */
    public function getGrammar()
    {
        return new CommunityGrammar();
    }

    /**
     * @return array The columns ti show as default in GridViewWidget
     */
    public function getGridViewColumns()
    {
        // TODO: Implement getGridViewColumns() method.
        return [];
    }

    /**
     * @return string The classname of the generic dashboard widget to access the plugin
     */
    public function getPluginWidgetClassname()
    {
        return WidgetIconCommunityDashboard::className();
    }

    /**
     *
     * @return type
     */
    public function sendNotification()
    {
//    $ret = false;
//
//    if ($this->context == self::className()) {
//      $ret = true;
//    }
//
//    return $ret;

        return ($this->context == self::className());
    }

    /**
     * Get Id of configuration record for network model Community
     * @return int $cwhConfigId
     */
    public static function getCwhConfigId()
    {
        //default newtwork configuration id = 3 for community
        $cwhConfigId = self::COMMUNITY_DEFAULT_NETWORK_ID;
        $cwhConfig = CwhConfig::findOne(['tablename' => self::tableName()]);
        if (!is_null($cwhConfig)) {
            $cwhConfigId = $cwhConfig->id;
        }

        return $cwhConfigId;
    }

    /**
     *
     * @return boolean
     */
    public function hasSubNetworks()
    {
        return true;
    }

    /**
     * Query for communities in user network.
     * @param int|null $userId - if null the logged userId is considered.
     * @return ActiveQuery
     */
    public function getUserNetworkQuery($userId = null, $params = [], $onlyActiveStatus = false)
    {
        if (empty($userId)) {
            $userId = Yii::$app->user->id;
        }

        $communitySearch = new CommunitySearch();
        return $communitySearch->buildQuery($params, 'own-interest', $onlyActiveStatus, $userId);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityManagerMms()
    {
        return $this->getCommunityUserMms()
            ->andWhere([\open20\amos\community\models\CommunityUserMm::tableName() . '.status' => \open20\amos\community\models\CommunityUserMm::STATUS_ACTIVE])
            ->andWhere([\open20\amos\community\models\CommunityUserMm::tableName() . '.role' => \open20\amos\community\models\CommunityUserMm::ROLE_COMMUNITY_MANAGER]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityManagers()
    {
        return $this->hasMany(\open20\amos\core\user\User::className(), ['id' => 'user_id'])->via('communityManagerMms');
    }

    /**
     * Return the recordset of all recipients relative to the networks
     * associated to the relative user (they may be different? check it!)
     *
     * @param type $networkIds
     * @param type $usersId
     */
    public function getListOfRecipients($networkIds = [], $usersId = [])
    {
        $query = new \yii\db\Query();
        $query->select([
            "concat('" . Community::tableName() . "', '-', community_user_mm.community_id) AS objID",
            'community.id', 'community.name', 'community.status', 'validated_once', 'visible_on_edit',
            'community.created_by', 'community.deleted_at',
            'community_user_mm.id', 'community_user_mm.community_id', 'community_user_mm.community_id AS reference',
            'community_user_mm.status', 'community_user_mm.user_id', 'community_user_mm.deleted_at'
        ])
            ->from(static::tableName())
            ->leftJoin('community_user_mm', 'community_user_mm.community_id = community.id
          AND community_user_mm.deleted_at IS NULL
          AND community_user_mm.status = \'' . CommunityUserMm::STATUS_ACTIVE . '\'')
            ->where(['community.id' => $networkIds])
            ->andWhere([
                'community_user_mm.user_id' => $usersId,
                'community.deleted_at' => null,
                'community.status' => self::COMMUNITY_WORKFLOW_STATUS_VALIDATED
            ]);

        return $query->all();
    }

    /**
     * @return bool
     */
    public function showParticipantWidget(){
        if($this->getAmosWidgetsIcons()->count() > 0) {
            $count = $this->getAmosWidgetsIcons()->andWhere(['classname' => 'open20\amos\admin\widgets\icons\WidgetIconUserProfile'])->count();
            
            return $count > 0;
        }
        
        return true;
    }

    /**
     * @return bool
     */
    public function showSubCommunityWidget(){
        if($this->getAmosWidgetsIcons()->count() > 0) {
            $count = $this->getAmosWidgetsIcons()->andWhere(['classname' => 'open20\amos\community\widgets\icons\WidgetIconCommunityDashboard'])->count();
            return $count > 0;
        }
        return true;
    }

    /**
     *
     */
    public function saveDashboardCommunity(){
        $id = $this->id;
        $canPersonalize = \Yii::$app->user->can('COMMUNITY_WIDGETS_ADMIN_PERSONALIZE');
        $ids = \Yii::$app->request->post('amosWidgetsIds');
        if($canPersonalize){
            CommunityAmosWidgetsMm::deleteAll(['community_id' => $id]);
        }else {
            CommunityAmosWidgetsMm::deleteAll(['community_id' => $id, 'personalized' => 0]);
        }

        foreach ((Array) $ids as $amos_widgets_id){
            $communityWidget = new CommunityAmosWidgetsMm();
            $communityWidget->community_id = $id;
            $communityWidget->amos_widgets_id = $amos_widgets_id;
            if($canPersonalize){
                $communityWidget->personalized = 1;
            }
            $communityWidget->save(false);
        }
    }
}