<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni;

use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\exceptions\AmosException;
use open20\amos\core\interfaces\BreadcrumbInterface;
use open20\amos\core\interfaces\InvitationExternalInterface;
use open20\amos\core\interfaces\OrganizationsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\invitations\models\Invitation;
use open20\amos\organizzazioni\components\ImportManager;
use open20\amos\organizzazioni\i18n\grammar\ProfiloGrammar;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloUserMm;
use open20\amos\organizzazioni\utility\OrganizzazioniUtility;
use open20\amos\organizzazioni\widgets\JoinedOrganizationsWidget;
use open20\amos\organizzazioni\widgets\JoinedOrgParticipantsTasksWidget;
use open20\amos\organizzazioni\widgets\ProfiloCardWidget;
use Yii;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use open20\amos\core\interfaces\CmsModuleInterface;

/**
 * Class Module
 * @package open20\amos\organizzazioni
 */
class Module
    extends AmosModule
    implements OrganizationsModuleInterface, SearchModuleInterface,
        InvitationExternalInterface, BreadcrumbInterface, CmsModuleInterface
{

    /**
     * @var Profilo|null $contextModelOrganization
     */
    protected $contextModelOrganization;

    /**
     * @var int|string|null $contextModelId
     */
    protected $contextModelId;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\organizzazioni\controllers';
    public $newFileMode = 0666;
    public $name = 'organizzazioni';

    /**
     * @var bool $enableMembershipOrganizations If true enable the membership
     * organizations. You can set a parent organization in form.
     */
    public $enableMembershipOrganizations = false;

    /**
     * @var array $defaultListViews This set the default order for the views in lists
     */
    public $defaultListViews = ['grid', 'icon'];

    /**
     * @var bool $enableAddOtherLegalHeadquarters If true it's possible to add
     * other legal headquarters. The headquarter type is visible in create
     * headquarter select.
     */
    public $enableAddOtherLegalHeadquarters = false;

    /**
     * @inheritdoc
     */
    public $db_fields_translation = [
        [
            'namespace' => 'open20\amos\organizzazioni\models\ProfiloEntiType',
            'attributes' => ['name'],
            'category' => 'amosorganizzazioni',
        ],
        [
            'namespace' => 'open20\amos\organizzazioni\models\ProfiloTipoStruttura',
            'attributes' => ['name'],
            'category' => 'amosorganizzazioni',
        ],
    ];

    /**
     * @var bool $enableSocial
     */
    public $enableSocial = false;

    /**
     * @var bool $oldStyleAddressEnabled
     */
    public $oldStyleAddressEnabled = false;

    /**
     * @var bool $enableOrganizationAttachments
     */
    public $enableOrganizationAttachments = true;

    /**
     * @var bool $enableSediRequired
     */
    public $enableSediRequired = true;

    /**
     * @var bool $enableCodeIstatRequired
     */
    public $enableCodeIstatRequired = true;

    /**
     * @var bool $enableRappresentanteLegaleText
     */
    public $enableRappresentanteLegaleText = false;

    /**
     * @var bool $forceSameSede
     */
    public $forceSameSede = false;

    /**
     * If true this configuration enable the user to request to join an organization
     * and a validator confirm the request.
     * The confirm can be made by legal representative, operative referee
     * and the validator.
     * @var bool $enableConfirmUsersJoinRequests
     */
    public $enableConfirmUsersJoinRequests = false;

    /**
     * @var array $htmlMailSubject
     */
    public $htmlMailSubject = [];

    /**
     * @var array $htmlMailContent
     */
    public $htmlMailContent = [];

    /**
     * @var bool $enabled_widget_organizzazioni
     */
    public $enabled_widget_organizzazioni = true;

    /**
     * @var bool $enabled_widget_sedi
     */
    public $enabled_widget_sedi = true;

    /**
     * @var bool $viewEmailEmployees
     */
    public $viewEmailEmployees = false;

    /**
     * @var bool $viewStatusEmployees
     */
    public $viewStatusEmployees = true;

    /**
     * @var bool $viewRoleEmployees
     */
    public $viewRoleEmployees = false;

    /**
     * @var bool $userNetworkWidgetSearchOrganization
     */
    public $userNetworkWidgetSearchOrganization = true;

    /**
     * @var bool $userNetworkWidgetSearchHeadquarter
     */
    public $userNetworkWidgetSearchHeadquarter = true;

    /**
     * @var bool $inviteUserOfOrganizationParent
     */
    public $inviteUserOfOrganizationParent = false;

    /**
     * @var bool $disableFieldChecks
     */
    public $disableFieldChecks = false;

    /**
     * If true this configuration enable Organizzazioni module manager to create
     * a reserved community.
     * These community can be made/managed by legal representative and operative referee.
     * @var bool $enableConfirmUsersJoinRequests
     */
    public $enableCommunityCreation = false;

    /**
     * @var bool
     */
    public $createCommunityAutomatically = false;

    /**
     * Set the community type when it's created.
     * By default, the community is closed.
     * @var int
     */
    public $communityType = CommunityType::COMMUNITY_TYPE_CLOSED;
    
    /**
     * Is community amos module loaded?
     *
     * @var \open20\amos\community\AmosCommunity
     */
    public $communityModule = null;

    /**
     * @var bool $enableProfiloEntiType
     */
    public $enableProfiloEntiType = true;

    /**
     * @var bool $enableProfiloTipologiaStruttura
     */
    public $enableProfiloTipologiaStruttura = false;

    /**
     * @var bool $enableContattoReferenteOperativo
     */
    public $enableContattoReferenteOperativo = false;

    /**
     * @var bool $externalInvitationUsers
     */
    public $externalInvitationUsers = false;

    /**
     * @var string $overrideUserContextAssociationStatus
     */
    public $overrideUserContextAssociationStatus = '';

    /**
     * @var bool $enableTipologiaOrganizzazione
     */
    public $enableTipologiaOrganizzazione = true;

    /**
     * @var bool $enableFormaLegale
     */
    public $enableFormaLegale = true;

    /**
     * @var bool $enableWorkflow
     */
    public $enableWorkflow = false;

    /**
     * @var bool $sendNotificationOnValidate
     */
    public $sendNotificationOnValidate = true;

    /**
     * @var bool $enableUniqueSecretCodeForInvitation
     */
    public $enableUniqueSecretCodeForInvitation = false;

    /**
     * @var array $addRequired
     */
    public $addRequired = [];

    /**
     * @var bool $enableProfiloGroups
     */
    public $enableProfiloGroups = false;

    /**
     * @var bool $excludeRefereesFromEployeesLists
     */
    public $excludeRefereesFromEployeesLists = false;

    /**
     * @var bool $directAccessToCommunityOrganization
     */
    public $directAccessToCommunityOrganization = false;

    /**
     * @var array $importOrganizationsConf Configuration array for the organization
     * importer. See README for the array structure.
     */
    public $importOrganizationsConf = [];

    /**
     * @var ImportManager $importManager
     */
    public $importManager = null;

    /**
     * @var disableAssociaButton $disableAssociaButton
     */
    public $disableAssociaButton = false;

    /**
     * @var bool
     */
    public $enableManageLinks = false;

    /**
     * Set to true to disable userNetworkWidget in user profile network
     * @var bool
     */
    public $hideUserNetworkWidget = false;

    /**
     * Default by task 18503 vapt check
     *
     * @var array
     */
    public $allowedFileExtensions = ['csv', 'doc', 'docx', 'pdf', 'rtf', 'txt', 'xls', 'xlsx', 'png', 'jpg', 'gif', 'bmp', 'jpeg', 'ppt', 'pptx'];
    
    public $employeesListView = "grid"; // grid, icon
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'open20\amos\organizzazioni\commands';
            \Yii::setAlias(
                '@open20/amos/'
                    . static::getModuleName()
                    . '/commands',
                __DIR__
                . '/commands'
            );
        }

        parent::init();

        \Yii::setAlias(
            '@open20/amos/'
                . static::getModuleName()
                . '/controllers/',
            __DIR__
            . '/controllers/'
        );
        
        $config = require(
            __DIR__
            . DIRECTORY_SEPARATOR
            . 'config'
            . DIRECTORY_SEPARATOR
            . 'config.php'
        );
        
        \Yii::configure(
            $this,
            ArrayHelper::merge(
                $config,
                ['params' => $this->params]
            )
        );

        $this->communityModule = Yii::$app->getModule('community');

        $this->importManager = new ImportManager([
            'importOrganizationsConf' => $this->importOrganizationsConf
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        $ns = __NAMESPACE__;
        return [
            'OrganizationsPlaces' => $ns . '\\models\OrganizationsPlaces',
            'Profilo' => $ns .  '\\models\Profilo',
            'ProfiloEntiType' => $ns .  '\\models\ProfiloEntiType',
            'ProfiloGroups' =>  $ns .  '\\models\ProfiloGroups',
            'ProfiloGroupsMm' =>  $ns .  '\\models\ProfiloGroupsMm',
            'ProfiloImport' =>  $ns .  '\\models\ProfiloImport',
            'ProfiloLegalForm' =>  $ns .  '\\models\ProfiloLegalForm',
            'ProfiloSedi' =>  $ns .  '\\models\ProfiloSedi',
            'ProfiloSediLegal' =>  $ns .  '\\models\ProfiloSediLegal',
            'ProfiloSediOperative' =>  $ns .  '\\models\ProfiloSediOperative',
            'ProfiloSediTypes' =>  $ns .  '\\models\ProfiloSediTypes',
            'ProfiloSediUserMm' =>  $ns .  '\\models\ProfiloSediUserMm',
            'ProfiloTypesPmi' =>  $ns .  '\\models\ProfiloTypesPmi',
            'ProfiloUserMm' =>  $ns .  '\\models\ProfiloUserMm',
            'ProfiloSearch' =>  $ns .  '\\models\search\ProfiloSearch',
            'ProfiloGroupsSearch' =>  $ns .  '\\models\search\ProfiloGroupsSearch',
            'ProfiloSediSearch' =>  $ns .  '\\models\search\ProfiloSediSearch',
            'ProfiloTipoStruttura' =>  $ns .  '\\models\ProfiloTipoStruttura',
        ];
    }

    /**
     * @return string
     */
    public static function getModuleName()
    {
        return 'organizzazioni';
    }

    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName()
    {
        return __NAMESPACE__ . '\models\search\ProfiloSearch';
    }

    /**
     * @inheritdoc
     */
    public static function getModuleIconName()
    {
        if (
            !empty(\Yii::$app->params['dashboardEngine'])
            && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS
        ) {
            return 'organizzazioni';
        }

        return 'building-o';
    }

    /**
     * @return |null
     */
    public function getWidgetGraphics()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getWidgetIcons()
    {
        return [
            \open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getOrganizationModelClass()
    {
        return $this->model('Profilo');
    }

    /**
     * @inheritdoc
     */
    public function getOrganizationCardWidgetClass()
    {
        return ProfiloCardWidget::class;
    }

    /**
     * @inheritdoc
     */
    public function getAssociateOrgsToProjectWidgetClass()
    {
        return JoinedOrganizationsWidget::class;
    }

    /**
     * @inheritdoc
     */
    public function getAssociateOrgsToProjectTaskWidgetClass()
    {
        return JoinedOrgParticipantsTasksWidget::class;
    }

    /**
     * @inheritdoc
     */
    public function getOrganizationsListQuery()
    {
        /** @var Profilo $profiloModel */
        $profiloModel = $this->createModel('Profilo');
        $query = $profiloModel::find();
        $query->orderBy(['name' => SORT_ASC]);

        return $query;
    }

    /**
     * 
     * @param type $user_id
     * @param type $organization_id
     * @param type $user_profile_role_id
     * @param type $user_profile_area_id
     * @param type $overrideStatus
     * @return boolean
     */
    public function saveOrganizationUserMm($user_id, $organization_id, $user_profile_role_id = null, $user_profile_area_id = null, $overrideStatus = '')
    {
        try {
            /** @var ProfiloUserMm $profiloUserMm */
            $profiloUserMm = $this->createModel('ProfiloUserMm');
            $org = $profiloUserMm::findOne([
                'profilo_id' => $organization_id,
                'user_id' => $user_id
            ]);
            
            if (empty($org)) {
                /** @var ProfiloUserMm $org */
                $org = $this->createModel('ProfiloUserMm');
                $org->profilo_id = $organization_id;
                $org->user_id = $user_id;
                if (!empty($user_profile_role_id)) {
                    $org->user_profile_role_id = $user_profile_role_id;
                }

                if (!empty($user_profile_area_id)) {
                    $org->user_profile_area_id = $user_profile_area_id;
                }

                if ($overrideStatus) {
                    $org->status = $overrideStatus;
                } else {
                    // TODO mettere questo quando sarà finita la modifica per
                    // la conferma dell'invito da parte dell'utente invitato
                    $org->status = ($this->enableConfirmUsersJoinRequests
                        ? $profiloUserMm::STATUS_WAITING_REQUEST_CONFIRM
                        : $profiloUserMm::STATUS_WAITING_OK_USER);
//                  $org->status = ($this->enableConfirmUsersJoinRequests ? $profiloUserMm::STATUS_WAITING_REQUEST_CONFIRM : $profiloUserMm::STATUS_ACTIVE);
                }

                return $org->save(false);
            }

            return true;
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);

            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function getOrganization($id)
    {
        $model = null;
        try {
            /** @var Profilo $profiloModel */
            $profiloModel = $this->createModel('Profilo');
            $model = $profiloModel::findOne(['id' => $id]);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function getUserOrganizations($userId)
    {
        return OrganizzazioniUtility::getUserOrganizations($userId);
    }

    /**
     * @inheritdoc
     */
    public function getUserHeadquarters($userId)
    {
        return OrganizzazioniUtility::getUserHeadquarters($userId);
    }

    /**
     * @return ProfiloGrammar
     */
    public function getGrammar()
    {
        return new ProfiloGrammar();
    }

    /**
     * @param int $userId
     * @param bool $onlyIds
     * @param bool $returnQuery
     * @return array|\yii\db\ActiveQuery|\yii\db\ActiveRecord[]
     * @throws AmosException
     */
    public function getOrganizationsRepresentedOrReferredByUserId($userId, $onlyIds = false, $returnQuery = false)
    {
        return OrganizzazioniUtility::getOrganizationsRepresentedOrReferredByUserId(
            $userId,
            $onlyIds,
            $returnQuery
        );
    }

    /**
     * @inheritdoc
     */
    public function addUserContextAssociation($userId, $modelId)
    {
        if (!is_numeric($modelId) && is_string($modelId)) {
            if (strpos($modelId, 'org-') === false) {
                return false;
            }
            /** @var Profilo $profiloModel */
            $profiloModel = $this->createModel('Profilo');
            $organization = $profiloModel::findBySecretCode($modelId);
            if (is_null($organization)) {
                return false;
            }
            $modelId = $organization->id;
        }
        if (strlen($this->overrideUserContextAssociationStatus) > 0) {
            return $this->saveOrganizationUserMm(
                $userId,
                $modelId,
                null,
                null,
                $this->overrideUserContextAssociationStatus
            );
        } else {
            return $this->saveOrganizationUserMm($userId, $modelId);
        }
    }

    /**
     * @inheridoc
     */
    public function getFrontEndMenu($dept = 1)
    {
        $menu = parent::getFrontEndMenu();
        $app = \Yii::$app;
        if (
            !$app->user->isGuest
            && \Yii::$app->user->can('LETTORE_ORGANIZZAZIONI')
        ) {
            $menu .= $this->addFrontEndMenu(
                Module::t('amosorganizzazioni', '#menu_front_organizzazioni'),
                Module::toUrlModule('/profilo/index'),
                $dept
            );
        }

        return $menu;
    }

    /**
     * This method is useful to find an organization by the context model id
     * of an invitation. Used only in the module.
     * @param int|string $contextModelId
     * @throws \yii\base\InvalidConfigException
     */
    protected function findOrganizationByContextModelId($contextModelId)
    {
        if (is_null($contextModelId)) {
            $this->contextModelOrganization = null;
            return;
        }
        
        if (
            is_null($this->contextModelOrganization)
            || ($this->contextModelId != $contextModelId)
        ) {
            $this->contextModelId = $contextModelId;
            /** @var Profilo $profiloModel */
            $profiloModel = $this->createModel('Profilo');
            if (strpos($contextModelId, 'org-') !== false) {
                $this->contextModelOrganization = $profiloModel::findBySecretCode($contextModelId);
            } else {
                $this->contextModelOrganization = $profiloModel::findOne($contextModelId);
            }
        }
    }

    /**
     * @param Invitation $invitation
     * @return string
     */
    public function renderInvitationEmailSubject($invitation)
    {
        $this->findOrganizationByContextModelId($invitation->context_model_id);
        $organizationName = !is_null($this->contextModelOrganization)
            ? $this->contextModelOrganization->name
            : '';
        
        return Module::t('amosorganizzazioni', '#invite_external_subject', [
            'platformName' => Yii::$app->name,
            'organizationName' => $organizationName
        ]);
    }

    /**
     * @param Invitation $invitation
     * @return string
     */
    public function renderInvitationEmailText($invitation)
    {
        $this->findOrganizationByContextModelId($invitation->context_model_id);
        return Yii::$app->controller->renderPartial('@vendor/open20/amos-organizzazioni/src/views/profilo/email/invitation_external_email', [
            'invitation' => $invitation,
            'organization' => $this->contextModelOrganization
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getIndexActions()
    {
        return [
            'profilo/index',
            'profilo/to-validate',
            'profilo/my-organizations',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getControllerNames()
    {
        return [
            'profilo' => self::t('amosorganizzazioni', "Organizzazioni"),
            'profilo-groups' => self::t('amosorganizzazioni', "#organizations_groups"),
            'profilo-sedi' => self::t('amosorganizzazioni', "Sedi"),
        ];
    }

    /*
     * CmsModuleInterface
     */

    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return Module::instance()->model('Profilo');
    }

}
