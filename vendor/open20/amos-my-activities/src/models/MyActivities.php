<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\models
 * @category   CategoryName
 */

namespace open20\amos\myactivities\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\base\UserProfile;
use open20\amos\admin\models\base\UserProfileValidationNotify;
use open20\amos\admin\models\UserContact;
use open20\amos\core\user\User;
use open20\amos\myactivities\AmosMyActivities;
use open20\amos\myactivities\basic\CommunityToValidate;
use open20\amos\myactivities\basic\DiscussionToValidate;
use open20\amos\myactivities\basic\DocumentToValidate;
use open20\amos\myactivities\basic\EenExpressionOfInterestToTakeover;
use open20\amos\myactivities\basic\EventToValidate;
use open20\amos\myactivities\basic\ExpressionOfInterestToEvaluate;
use open20\amos\myactivities\basic\InnovativeSolutionToValidate;
use open20\amos\myactivities\basic\MyActivitiesList;
use open20\amos\myactivities\basic\NewsToValidate;
use open20\amos\myactivities\basic\OrganizationsToValidate;
use open20\amos\myactivities\basic\PartnershipProfileToValidate;
use open20\amos\myactivities\basic\ProfileValidationNotifyToRead;
use open20\amos\myactivities\basic\ProfiloToValidate;
use open20\amos\myactivities\basic\ReportToRead;
use open20\amos\myactivities\basic\RequestExternalFacilitator;
use open20\amos\myactivities\basic\RequestToJoinOrganizzazioniForEmployees;
use open20\amos\myactivities\basic\RequestToJoinOrganizzazioniForReferees;
use open20\amos\myactivities\basic\RequestToJoinOrganizzazioniSediForReferees;
use open20\amos\myactivities\basic\RequestToParticipateCommunity;
use open20\amos\myactivities\basic\RequestToParticipateCommunityForManager;
use open20\amos\myactivities\basic\ResultsProposalToValidate;
use open20\amos\myactivities\basic\ResultsToValidate;
use open20\amos\myactivities\basic\ShowcaseProjectProposalToValidate;
use open20\amos\myactivities\basic\ShowcaseProjectToValidate;
use open20\amos\myactivities\basic\ShowcaseProjectUserToAccept;
use open20\amos\myactivities\basic\TerritoryToValidate;
use open20\amos\myactivities\basic\UserProfileActivationRequest;
use open20\amos\myactivities\basic\UserProfileToValidate;
use open20\amos\myactivities\basic\WaitingContacts;
use open20\amos\myactivities\models\search\MyActivitiesModelSearch;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class MyActivities
 * @package open20\amos\myactivities\models
 */
class MyActivities extends Model
{
    /**
     * @var MyActivitiesList $myActivitiesList
     */
    protected $myActivitiesList;

    /**
     * @var array $queryParams
     */
    protected $queryParams = [];

    /**
     * @var int $user_id
     */
    protected $user_id;

    /**
     * @var bool $countOnly
     */
    static $countOnly = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->myActivitiesList = new MyActivitiesList();
        $this->user_id          = Yii::$app->user->id;
        if (\Yii::$app instanceof \yii\web\Application) {
            $this->queryParams = Yii::$app->request->getQueryParams();
        }

        parent::init();
    }

    /**
     * @param bool $count
     * @return bool|int|mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getCountActivities($count = false)
    {
        self::$countOnly = $count;
        /** @var MyActivities $model */
        $model           = AmosMyActivities::instance()->createModel('MyActivities');

        $allMyActivities = $model->getMyActivities(null, false);

        $counter = 0;

        foreach ($allMyActivities as $activity => $count) {
            $counter = $counter + intval($count);
        }

        return $counter;
    }

    /**
     * @param MyActivitiesModelSearch|null $modelSearch
     * @param bool $enableOrder
     * @return array
     * @throws \open20\amos\core\exceptions\AmosException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function getMyActivities($modelSearch = null, $enableOrder = true)
    {
        $this->myActivitiesList->addModelSet($this->getWaitingContacts());
        $this->myActivitiesList->addModelSet($this->getNewsToValidate($enableOrder));
        $this->myActivitiesList->addModelSet($this->getProfiloToValidate($enableOrder));
        $this->myActivitiesList->addModelSet($this->getRequestToParticipateCommunity());
        $this->myActivitiesList->addModelSet($this->getUserProfileToValidate());
        $this->myActivitiesList->addModelSet($this->getComunityToValidate($enableOrder));
        $this->myActivitiesList->addModelSet($this->getDiscussionToValidate($enableOrder));
        $this->myActivitiesList->addModelSet($this->getDocumentToValidate($enableOrder));

        $this->myActivitiesList->addModelSet($this->getEventsToValidate($enableOrder));

        $this->myActivitiesList->addModelSet($this->getOrganizationsToValidate($enableOrder));

        $this->myActivitiesList->addModelSet($this->getShowcaseProjectToValidate($enableOrder));
        $this->myActivitiesList->addModelSet($this->getResultsToValidate($enableOrder));
        $this->myActivitiesList->addModelSet($this->getEenExpressionOfInterestToTakeover($enableOrder));
        $this->myActivitiesList->addModelSet($this->getProposalResultsToValidate($enableOrder));
        $this->myActivitiesList->addModelSet($this->getPartnershipProfilesToValidate($enableOrder));
        $this->myActivitiesList->addModelSet($this->getUserProfileActivationRequest());
        $this->myActivitiesList->addModelSet($this->getShowcaseProjectUserToAccept($enableOrder));
        $this->myActivitiesList->addModelSet($this->getShowcaseProjectProposalToValidate($enableOrder));

        /* NOT IMPLEMENTED
          $this->myActivitiesList->addModelSet($this->getSurveyToValidate());
          $this->myActivitiesList->addModelSet($this->getExpressionOfInterestToEvaluate());
         */

        $this->myActivitiesList->addModelSet($this->getRequestToParticipateCommunityForManager());
        $this->myActivitiesList->addModelSet($this->getReportToRead());
        $this->myActivitiesList->addModelSet($this->getProfileValidationNotifyToRead());
        $this->myActivitiesList->addModelSet($this->getRequestToJoinOrganizzazioniForReferees());
        $this->myActivitiesList->addModelSet($this->getRequestToJoinOrganizzazioniSediForReferees());
        $this->myActivitiesList->addModelSet($this->getRequestToJoinOrganizzazioniForEmployees());
        $this->myActivitiesList->addModelSet($this->getRequestExternalFacilitator());
        $this->myActivitiesList->addModelSet($this->getTerritoryToValidate($enableOrder));
        $this->myActivitiesList->addModelSet($this->getInnovativeSolutionsToValidate($enableOrder));


        if (self::$countOnly == false) {
            $this->myActivitiesList->applySort($modelSearch);
            $this->myActivitiesList->applyFilter($modelSearch);
        }

        return $this->myActivitiesList->getMyActivitiesList();
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getWaitingContacts()
    {
        if (Yii::$app->hasModule(AmosAdmin::getModuleName())) {
            /** @var ActiveQuery $query */
            $query = WaitingContacts::find()
                ->innerJoinWith('user')
                ->andWhere([
                WaitingContacts::tableName().'.contact_id' => $this->user_id,
                WaitingContacts::tableName().'.status' => UserContact::STATUS_INVITED
            ]);

            if (self::$countOnly) {
                return [WaitingContacts::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @param bool $enableOrder
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getNewsToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('news')) {
            $modelSearch  = new NewsToValidate();
            /** @var ActiveDataProvider $dataProvider */
            $dataProvider = $modelSearch->searchToValidateNews($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = NewsToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [NewsToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }
    
    /**
     * @param bool $enableOrder
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getProfiloToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('organizzazioni')) {
            $modelSearch = new ProfiloToValidate();
            /** @var ActiveDataProvider $dataProvider */
            $dataProvider = $modelSearch->searchToValidateProfilo($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = ProfiloToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [ProfiloToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @param bool $enableOrder
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getTerritoryToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('landing')) {
            $modelSearch  = new TerritoryToValidate();
            /** @var ActiveDataProvider $dataProvider */
            $dataProvider = $modelSearch->searchToValidate($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = TerritoryToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [TerritoryToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @param bool $enableOrder
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getInnovativeSolutionsToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('innovativesolutions')) {
            $modelSearch = new InnovativeSolutionToValidate();
            /** @var ActiveDataProvider $dataProvider */
            $dataProvider = $modelSearch->searchSolutionToValidateMyActivities();
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = InnovativeSolutionToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [InnovativeSolutionToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getRequestToParticipateCommunity()
    {
        if (Yii::$app->hasModule('community')) {
            /** @var ActiveQuery $query */
            $query = RequestToParticipateCommunity::find()
                ->joinWith('community')
                ->andWhere([
                'community.validated_once' => 1,
                'community_user_mm.user_id' => $this->user_id,
                'community_user_mm.status' => \open20\amos\community\models\CommunityUserMm::STATUS_WAITING_OK_USER
            ]);

            if (self::$countOnly) {
                return [RequestToParticipateCommunity::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getUserProfileToValidate()
    {
        $moduleAdmin = Yii::$app->getModule(AmosAdmin::getModuleName());
        if ($moduleAdmin) {
            /** @var ActiveQuery|null $query */
            $query = null;
            if (\Yii::$app->user->can('FACILITATOR')) {
                /** @var User $user */
                $user  = Yii::$app->user->identity;
                $query = UserProfileToValidate::find()
                    ->andWhere([
                    'status' => \open20\amos\admin\models\UserProfile::USERPROFILE_WORKFLOW_STATUS_TOVALIDATE,
                    'attivo' => 1,
                    'facilitatore_id' => $user->userProfile->id
                ]);
            } else if (!$moduleAdmin->enableExternalFacilitator && \Yii::$app->user->can('VALIDATOR')) {
                $query = UserProfileToValidate::find()
                    ->andWhere([
                    'status' => \open20\amos\admin\models\UserProfile::USERPROFILE_WORKFLOW_STATUS_TOVALIDATE,
                    'attivo' => 1
                ]);
            }


            if (self::$countOnly) {
                return [UserProfileToValidate::className() => (!empty($query) ? $query->asArray()->count() : 0)];
            }

            return (!empty($query) ? $query->all() : []);
        }

        return [];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getUserProfileActivationRequest()
    {
        if (Yii::$app->hasModule(AmosAdmin::getModuleName())) {
            if (Yii::$app->user->can('ADMIN')) {
                /** @var ActiveQuery $query */
                $query = UserProfileActivationRequest::find()
                    ->innerJoinWith('userProfileReactivationRequest')
                    ->andWhere(['user_profile.attivo' => 0]);

                if (self::$countOnly) {
                    return [UserProfileActivationRequest::className() => $query->asArray()->count()];
                }

                return $query->all();
            }
        }

        return [];
    }

    /**
     * @param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getComunityToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('community')) {
            $communitySearch = new CommunityToValidate;
            $dataProvider    = $communitySearch->searchToValidateCommunities($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = CommunityToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [CommunityToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @@param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getDiscussionToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('discussioni')) {
            $modelSearch = new DiscussionToValidate;

//            $notifyModule = \Yii::$app->getModule('notify');
//            if ($notifyModule) {
//                $modelSearch->setNotifier(new \open20\amos\notificationmanager\base\NotifyWidgetDoNothing());
//            }

            $dataProvider = $modelSearch->searchToValidate($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = DiscussionToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [DiscussionToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getDocumentToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('documenti')) {
            $modelSearch  = new DocumentToValidate();
            $dataProvider = $modelSearch->searchToValidateDocuments($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');
            /** @var ActiveQuery $query */
            $query = DocumentToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [DocumentToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @@param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getEventsToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('events')) {
            $modelSearch  = new EventToValidate();
            /** @var ActiveDataProvider $dataProvider */
            $dataProvider = $modelSearch->searchToValidate($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = EventToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [EventToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getOrganizationsToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('organizations')) {
            $modelSearch  = new OrganizationsToValidate();
            $dataProvider = $modelSearch
                ->searchToValidateOrganizations($this->queryParams);

            if (!$enableOrder) {
                $dataProvider->sort = false;
            }

            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = OrganizationsToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [OrganizationsToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @return array|RequestToJoinOrganizzazioniForReferees[]
     * @throws \open20\amos\core\exceptions\AmosException
     * @throws \yii\base\InvalidConfigException
     */
    protected function getRequestToJoinOrganizzazioniForReferees()
    {
        $organizzazioniModule = Yii::$app->getModule('organizzazioni');
        /** @var \open20\amos\organizzazioni\Module $organizzazioniModule */
        if (!is_null($organizzazioniModule) && $organizzazioniModule->hasProperty('enableConfirmUsersJoinRequests') && $organizzazioniModule->enableConfirmUsersJoinRequests
        ) {
            $organizationsIds = $organizzazioniModule
                ->getOrganizationsRepresentedOrReferredByUserId($this->user_id, true);

            /** @var UserProfile $userProfileModel */
            $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');

            $userProfileTable                            = $userProfileModel::tableName();
            $requestToJoinOrganizzazioniForRefereesTable = RequestToJoinOrganizzazioniForReferees::tableName();

            /** @var ActiveQuery $query */
            $query = RequestToJoinOrganizzazioniForReferees::find()
                ->innerJoin(
                    $userProfileTable,
                    $userProfileTable.'.user_id = '.$requestToJoinOrganizzazioniForRefereesTable.'.user_id AND '
                    .$userProfileTable.'.deleted_at IS NULL'
                )
                ->andWhere([
                'profilo_id' => $organizationsIds,
                $requestToJoinOrganizzazioniForRefereesTable.'.status' => RequestToJoinOrganizzazioniForReferees::STATUS_WAITING_REQUEST_CONFIRM
            ]);

            if (self::$countOnly) {
                return [RequestToJoinOrganizzazioniForReferees::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @return array|RequestToJoinOrganizzazioniForEmployees[]
     * @throws \open20\amos\core\exceptions\AmosException
     * @throws \yii\base\InvalidConfigException
     */
    protected function getRequestToJoinOrganizzazioniForEmployees()
    {
        $organizzazioniModule = Yii::$app->getModule('organizzazioni');
        /** @var \open20\amos\organizzazioni\Module $organizzazioniModule */
        if (!is_null($organizzazioniModule)
            && $organizzazioniModule->hasProperty('enableConfirmUsersJoinRequests')
            && $organizzazioniModule->enableConfirmUsersJoinRequests
            && defined(RequestToJoinOrganizzazioniForEmployees::className() . '::STATUS_WAITING_OK_USER')
        ) {
            /** @var UserProfile $userProfileModel */
            $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');

            $userProfileTable = $userProfileModel::tableName();
            $requestsTable    = RequestToJoinOrganizzazioniForEmployees::tableName();

            /** @var ActiveQuery $query */
            $query = RequestToJoinOrganizzazioniForEmployees::find()
                ->innerJoin(
                    $userProfileTable,
                    $userProfileTable.'.user_id = '.$requestsTable.'.user_id AND '
                    .$userProfileTable.'.deleted_at IS NULL'
                )
                ->andWhere([$requestsTable.'.user_id' => $this->user_id])
                ->andWhere([$requestsTable.'.status' => RequestToJoinOrganizzazioniForEmployees::STATUS_WAITING_OK_USER]);

            if (self::$countOnly) {
                return [RequestToJoinOrganizzazioniForEmployees::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @return array|RequestToJoinOrganizzazioniSediForReferees[]
     * @throws \open20\amos\core\exceptions\AmosException
     * @throws \yii\base\InvalidConfigException
     */
    protected function getRequestToJoinOrganizzazioniSediForReferees()
    {
        $organizzazioniModule = Yii::$app->getModule('organizzazioni');
        /** @var \open20\amos\organizzazioni\Module $organizzazioniModule */
        if (!is_null($organizzazioniModule) && $organizzazioniModule->hasProperty('enableConfirmUsersJoinRequests') && $organizzazioniModule->enableConfirmUsersJoinRequests
        ) {
            $organizationsIds = $organizzazioniModule
                ->getOrganizationsRepresentedOrReferredByUserId($this->user_id, true);

            /** @var UserProfile $userProfileModel */
            $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
            /** @var ProfiloSedi $profiloSediModel */
            $profiloSediModel = \open20\amos\organizzazioni\Module::instance()->createModel('ProfiloSedi');

            $userProfileTable                                = $userProfileModel::tableName();
            $requestToJoinOrganizzazioniSediForRefereesTable = RequestToJoinOrganizzazioniSediForReferees::tableName();

            /** @var ActiveQuery $query */
            $query = RequestToJoinOrganizzazioniSediForReferees::find()
                ->innerJoin($userProfileTable,
                    $userProfileTable.'.user_id = '.$requestToJoinOrganizzazioniSediForRefereesTable.'.user_id AND '
                    .$userProfileTable.'.deleted_at IS NULL'
                )
                ->innerJoinWith('profiloSedi')
                ->andWhere([
                $profiloSediModel::tableName().'.profilo_id' => $organizationsIds,
                $requestToJoinOrganizzazioniSediForRefereesTable.'.status' => RequestToJoinOrganizzazioniSediForReferees::STATUS_WAITING_REQUEST_CONFIRM
            ]);

            if (self::$countOnly) {
                return [RequestToJoinOrganizzazioniSediForReferees::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @param bool $enableOrder
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getEenExpressionOfInterestToTakeover($enableOrder)
    {
        if (Yii::$app->hasModule('een')) {
            $modelSearch  = new EenExpressionOfInterestToTakeover();
            $dataProvider = $modelSearch->searchEoiToTakeOver($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = EenExpressionOfInterestToTakeover::find()
                ->andWhere(['een_expr_of_interest.id' => $ids]);

            if (self::$countOnly) {
                return [EenExpressionOfInterestToTakeover::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getRequestToParticipateCommunityForManager()
    {

        if (Yii::$app->hasModule('community')) {
            $communityModule = Yii::$app->getModule('community');
            $communitiesIds  = $communityModule->getCommunitiesManagedByUserId($this->user_id, true);
            if (count($communitiesIds) > 0) {
                /** @var ActiveQuery $query */
                $query = RequestToParticipateCommunityForManager::find()
                    ->innerJoin(
                        UserProfile::tableName(),
                        UserProfile::tableName().'.user_id = '.RequestToParticipateCommunityForManager::tableName()
                        .'.user_id and '.UserProfile::tableName().'.deleted_at is NULL'
                    )
                    ->andWhere([
                    'community_id' => $communitiesIds,
                    RequestToParticipateCommunityForManager::tableName().'.status' => \open20\amos\community\models\CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER
                ]);

                if (self::$countOnly) {
                    return [RequestToParticipateCommunityForManager::className() => $query->asArray()->count()];
                }

                return $query->all();
            }
        }

        return [];
    }
    
    public function getProfileValidationNotifyToRead()
    {
        if (self::$countOnly) {
            return [RequestToParticipateCommunityForManager::className() => ProfileValidationNotifyToRead::find()->andWhere(['user_id' => Yii::$app->user->id])->asArray()->count()];
        }
        return ProfileValidationNotifyToRead::find()->andWhere(['user_id' => Yii::$app->user->id])->all();
    }


    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getReportToRead()
    {
        if (Yii::$app->hasModule('report')) {
            $reportMyInterest = [];
            $reportModule     = Yii::$app->getModule('report');
            $ids              = $reportModule->getOwnUnreadReports()->select('report.id');

            $allReport = ReportToRead::find()
                ->andWhere(['id' => $ids])
                ->andWhere(['read_at' => null])
                ->all();

            foreach ($allReport as $report) {
                if (Yii::$app->hasModule('news') && ($report->classname == \open20\amos\news\models\News::className())) {
                    $model = \open20\amos\news\models\News::find()
                        ->andWhere(['id' => $report->context_id])
                        ->one();

                    if (!empty($model)) {
                        // Check if user logged is creator
                        if ($model->created_by == $this->user_id) {
                            $reportMyInterest[] = $report;
                            continue;
                        }

                        // Check if user logged is facilitator
                        /** @var UserProfile $userProfileReport */
                        $userProfileReport = UserProfile::find()
                            ->andWhere(['user_id' => $model->created_by])
                            ->one();
                        if ($userProfileReport->facilitatore_id == $this->user_id) {
                            $reportMyInterest[] = $report;
                            continue;
                        }

                        // Check if user logged is validator
                        $valUserId = $model
                            ->getStatusLastUpdateUser(\open20\amos\news\models\News::NEWS_WORKFLOW_STATUS_VALIDATO);
                        if (!is_null($valUserId) && ($valUserId == $this->user_id)) {
                            $reportMyInterest[] = $report;
                            continue;
                        }
                    }
                }

                if (Yii::$app->hasModule('discussioni') && ($report->classname == \open20\amos\discussioni\models\DiscussioniTopic::className())) {
                    $model = \open20\amos\discussioni\models\DiscussioniTopic::find()
                        ->andWhere(['id' => $report->context_id])
                        ->one();
                    if (!empty($model)) {
                        // Check if user logged is creator
                        if ($model->created_by == $this->user_id) {
                            $reportMyInterest[] = $report;
                            continue;
                        }

                        // Check if user logged is facilitator
                        /** @var UserProfile $userProfileReport */
                        $userProfileReport = UserProfile::find()
                            ->andWhere(['user_id' => $model->created_by])
                            ->one();
                        if ($userProfileReport->facilitatore_id == $this->user_id) {
                            $reportMyInterest[] = $report;
                            continue;
                        }

                        // Check if user logged is validator
                        $valUserId = $model->getStatusLastUpdateUser(
                            \open20\amos\discussioni\models\DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA
                        );
                        if (!is_null($valUserId) && ($valUserId == $this->user_id)) {
                            $reportMyInterest[] = $report;
                            continue;
                        }
                    }
                }

                if (Yii::$app->hasModule('documenti') && ($report->classname == \open20\amos\documenti\models\Documenti::className())) {
                    $model = \open20\amos\documenti\models\Documenti::find()
                        ->andWhere(['id' => $report->context_id])
                        ->one();
                    if (!empty($model)) {
                        // Check if user logged is creator
                        if ($model->created_by == $this->user_id) {
                            $reportMyInterest[] = $report;
                            continue;
                        }
                        // Check if user logged is facilitator
                        /** @var UserProfile $userProfileReport */
                        $userProfileReport = UserProfile::find()
                            ->andWhere(['user_id' => $model->created_by])
                            ->one();
                        if ($userProfileReport->facilitatore_id == $this->user_id) {
                            $reportMyInterest[] = $report;
                            continue;
                        }

                        // Check if user logged is validator
                        $valUserId = $model
                            ->getStatusLastUpdateUser(\open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO);
                        if (!is_null($valUserId) && ($valUserId == $this->user_id)) {
                            $reportMyInterest[] = $report;
                            continue;
                        }
                    }
                }

                if (Yii::$app->hasModule('community') && ($report->classname == \open20\amos\community\models\Community::className())) {
                    $model = \open20\amos\community\models\Community::find()
                        ->andWhere(['id' => $report->context_id])
                        ->one();
                    if (!empty($model)) {
                        // Check if user logged is creator
                        if ($model->created_by == $this->user_id) {
                            $reportMyInterest[] = $report;
                            continue;
                        }
                        // Check if user logged is facilitator
                        /** @var UserProfile $userProfileReport */
                        $userProfileReport = UserProfile::find()
                            ->andWhere(['user_id' => $model->created_by])
                            ->one();
                        if ($userProfileReport->facilitatore_id == $this->user_id) {
                            $reportMyInterest[] = $report;
                            continue;
                        }
                        // Check if user logged is validator
                        $valUserId = $model
                            ->getStatusLastUpdateUser(\open20\amos\community\models\Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED);
                        if (!is_null($valUserId) && ($valUserId == $this->user_id)) {
                            $reportMyInterest[] = $report;
                            continue;
                        }
                    }
                }
            }

            if (self::$countOnly) {
                return [ReportToRead::className() => count($reportMyInterest)];
            }

            return $reportMyInterest;
        }

        return [];
    }

    /**
     * TODO Not yet terminated
     * @return array
     */
    protected function getSurveyToValidate()
    {
        if (Yii::$app->hasModule('sondaggi')) {
            return []; // SurveyToValidate::find()->all();
        }

        return [];
    }

    /**
     * @@param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getShowcaseProjectToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('showcaseprojects')) {
            $modelSearch  = new ShowcaseProjectToValidate();
            $dataProvider = $modelSearch->searchToValidateShowcaseProjects($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = ShowcaseProjectToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [ShowcaseProjectToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @@param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getShowcaseProjectProposalToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('showcaseprojects')) {
            $modelSearch  = new ShowcaseProjectProposalToValidate();
            $dataProvider = $modelSearch->searchToValidateShowcaseProjectProposals($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = ShowcaseProjectProposalToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [ShowcaseProjectProposalToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getShowcaseProjectUserToAccept($enableOrder)
    {
        if (Yii::$app->hasModule('showcaseprojects')) {
            $modelSearch = new ShowcaseProjectUserToAccept();
            $query       = $modelSearch->searchShowcaseProjectUserToAccept($this->queryParams);

            $ids = ArrayHelper::map($query->all(), 'id', 'id');

            /** @var ActiveQuery $query */
            $query = ShowcaseProjectUserToAccept::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [ShowcaseProjectUserToAccept::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @@param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getResultsToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('results')) {
            $modelSearch  = new ResultsToValidate();
            $dataProvider = $modelSearch->searchToValidateResults($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = ResultsToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [ResultsToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @@param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getProposalResultsToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('results')) {
            $modelSearch  = new ResultsProposalToValidate();
            $dataProvider = $modelSearch->searchToValidateResultProposals($this->queryParams);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = ResultsProposalToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [ResultsProposalToValidate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @@param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    protected function getPartnershipProfilesToValidate($enableOrder)
    {
        if (Yii::$app->hasModule('partnershipprofiles')) {
            $modelSearch  = new PartnershipProfileToValidate();
            $dataProvider = $modelSearch->searchForFacilitator($this->queryParams);
            $dataProvider->query->andWhere(['status' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE]);
            if (!$enableOrder) {
                $dataProvider->sort = false;
            }
            $ids = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = PartnershipProfileToValidate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [PartnershipProfileToValidate::className() => $query->asArray()->count()];
            }
            return $query->all();
        }

        return [];
    }

    /**
     * @@param bool $enableOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    protected function getRequestExternalFacilitator()
    {
        if (Yii::$app->hasModule(AmosAdmin::getModuleName())) {
            $modelSearch  = new RequestExternalFacilitator();
            $dataProvider = $modelSearch->searchRequestPending($this->queryParams);
            $ids          = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = RequestExternalFacilitator::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [RequestExternalFacilitator::className() => $query->asArray()->count()];
            }
            return $query->all();
        }
        return [];
    }

    /**
     * TODO Not yet terminated
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getExpressionOfInterestToEvaluate()
    {
        if (Yii::$app->hasModule('partnershipprofiles')) {
            $modelSearch  = new ExpressionOfInterestToEvaluate();
            $dataProvider = $modelSearch->searchForFacilitator($this->queryParams);
            $ids          = ArrayHelper::map($dataProvider->models, 'id', 'id');

            /** @var ActiveQuery $query */
            $query = ExpressionOfInterestToEvaluate::find()
                ->andWhere(['id' => $ids]);

            if (self::$countOnly) {
                return [ExpressionOfInterestToEvaluate::className() => $query->asArray()->count()];
            }

            return $query->all();
        }

        return [];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return AmosMyActivities::t('amosmyactivities', 'My activities');
    }
}