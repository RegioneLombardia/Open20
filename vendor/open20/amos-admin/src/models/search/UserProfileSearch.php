<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models\search
 * @category   CategoryName
 */

namespace open20\amos\admin\models\search;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\UserProfileClassesUserMm;
use open20\amos\core\user\User;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\SearchResult;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use open20\amos\admin\models\UserContact;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;

/**
 * Class UserProfileSearch
 *
 * UserProfileSearch represents the model behind the search form about `common\models\UserProfile`.
 *
 * @property string $email
 *
 * @package open20\amos\admin\models\search
 */
class UserProfileSearch extends UserProfile implements SearchModelInterface, CmsModelInterface {

    /**
     * @var string $username
     */
    public $username = '';

    /**
     * @var string $email
     */
    public $email = '';

    /**
     * @var bool $isFacilitator
     */
    public $isFacilitator;

    /**
     * @var bool $isOperatingReferent
     */
    public $isOperatingReferent;

    /**
     * @var string $userProfileStatus
     */
    public $userProfileStatus = '';
    public $tags;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'privacy'], 'integer'],
            [[
            'nome',
            'cognome',
            'username',
            'email',
            'sesso',
            'codice_fiscale',
            'prevalent_partnership_id',
            'user_profile_role_id',
            'user_profile_area_id',
            'user_profile_area_other',
            'facilitatore_id',
            'status',
            'isFacilitator',
            'isOperatingReferent',
            'userProfileStatus',
            'validato_almeno_una_volta',
            'tags',
            'last_online',
                ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return ArrayHelper::merge(parent::attributeLabels(),
                        [
                            'userProfileStatus' => AmosAdmin::t('amosadmin', 'Stato profilo utente'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * This is the base search.
     * @param array $params
     * @return ActiveQuery
     */
    public function baseSearch($params) {
        /** @var ActiveQuery $query */
        $query = AmosAdmin::instance()->createModel('UserProfile')->find()->innerJoinWith(['user']);

        /** @var AmosAdmin $adminModule */
        $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());

        if (
                !is_null(Yii::$app->getModule($adminModule->getOrganizationModuleName())) &&
                $this->adminModule->confManager->isVisibleBox('box_prevalent_partnership',
                        ConfigurationManager::VIEW_TYPE_FORM) &&
                $this->adminModule->confManager->isVisibleField('prevalent_partnership_id',
                        ConfigurationManager::VIEW_TYPE_FORM)
        ) {
            $query->joinWith(['prevalentPartnership']);
        }

        $cwh = Yii::$app->getModule("cwh");
        // if we are navigating users inside a sprecific entity (eg. a community)
        // see users filtered by entity-user association table
        if (isset($cwh)) {
            $cwh->setCwhScopeFromSession();
            if (!empty($cwh->userEntityRelationTable)) {
                $mmTable = $cwh->userEntityRelationTable['mm_name'];
                $mmTableAlis = 'u2';
                $entityField = $cwh->userEntityRelationTable['entity_id_field'];
                $entityId = $cwh->userEntityRelationTable['entity_id'];
                $query
                        ->innerJoin($mmTable . ' ' . $mmTableAlis, $mmTableAlis . '.user_id = user_profile.user_id ')
                        ->andWhere([
                            $mmTableAlis . '.' . $entityField => $entityId
                ]);
            }
        }

        // Init the default search values
        $this->initOrderVars();

        // Check params to get orders value
        $this->setOrderVars($params);

        //INIZIO ACCOPPIAMENTO STRETTO CON ALTRA ENTITA'
        if ($adminModule->tightCoupling == true && !\Yii::$app->user->can($adminModule->tightCouplingRoleAdmin)) {

            $tightCouplingModel = null;
            $tightCouplingField = null;
            if (!empty($adminModule->tightCouplingModel) && is_array($adminModule->tightCouplingModel)) {
                foreach ($adminModule->tightCouplingModel as $k => $v) {
                    $tightCouplingModel = $k;
                    $tightCouplingField = $v;
                }
            }

            if (!empty($tightCouplingModel) && !empty($tightCouplingField)) {
                $myUserId = \Yii::$app->user->id;
                $myGroups = $tightCouplingModel::find()
                        ->andWhere(["{$tightCouplingModel::tableName()}.user_id" => $myUserId])
                        ->select("{$tightCouplingModel::tableName()}.{$tightCouplingField}");
                $query->innerJoin($tightCouplingModel::tableName(),
                                "{$tightCouplingModel::tableName()}.user_id = user_profile.user_id")
                        ->andWhere(['in', "{$tightCouplingModel::tableName()}.{$tightCouplingField}", $myGroups]);
            }
        }

        $query->groupBy('user_profile.id');
        //FINE ACCOPPIAMENTO STRETTO CON ALTRA ENTITA'

        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @return mixed
     */
    public function baseFilter($query) {
        $query->andFilterWhere([
            UserProfile::tableName() . '.status' => $this->userProfileStatus,
            UserProfile::tableName() . '.validato_almeno_una_volta' => $this->validato_almeno_una_volta,
            UserProfile::tableName() . '.last_online' => $this->last_online,
        ]);

        if (!empty($this->tags)) {
            $query->innerJoin('cwh_tag_owner_interest_mm',
                            'cwh_tag_owner_interest_mm.record_id = ' . UserProfile::tableName() . '.id and cwh_tag_owner_interest_mm.deleted_at is null')
                    ->andFilterWhere(['cwh_tag_owner_interest_mm.tag_id' => $this->tags]);
        }

        $query->andFilterWhere(['like', UserProfile::tableName() . '.nome', $this->nome])
                ->andFilterWhere(['like', UserProfile::tableName() . '.cognome', $this->cognome])
                ->andFilterWhere(['like', User::tableName() . '.username', $this->username])
                ->andFilterWhere(['codice_fiscale' => $this->codice_fiscale])
                ->andFilterWhere(['like', User::tableName() . '.email', $this->email]);

        if (
                $this->adminModule->confManager->isVisibleBox('box_prevalent_partnership',
                        ConfigurationManager::VIEW_TYPE_FORM) &&
                $this->adminModule->confManager->isVisibleField('prevalent_partnership_id',
                        ConfigurationManager::VIEW_TYPE_FORM)
        ) {
            $this->userProfileSelectFieldsQuery($query, 'prevalent_partnership_id');
        }
        $this->userProfileSelectFieldsQuery($query, 'facilitatore_id');

        // If value is "-1" it mean the user is searching whether the sex value is not selected.
        if ($this->sesso == -1) {
            $query->andWhere(['or', [UserProfile::tableName() . '.sesso' => null], [UserProfile::tableName() . '.sesso' => '']]);
        } else {
            $query->andFilterWhere([
                UserProfile::tableName() . '.sesso' => $this->sesso
            ]);
        }

        $this->userProfileRolesQuery($query, 'isFacilitator', 'FACILITATOR');
        $organizationModuleName = $this->adminModule->getOrganizationModuleName();
        if (($organizationModuleName == 'organizations') && !is_null(Yii::$app->getModule($organizationModuleName))) {
            $this->userProfileRolesQuery($query, 'isOperatingReferent', 'OPERATING_REFERENT');
        }

        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @param string $fieldName
     */
    protected function userProfileSelectFieldsQuery($query, $fieldName) {
        // If value is "-1" it mean the user is searching whether the prevalent partnership is not selected.
        if ($this->{$fieldName} == -1) {
            $query->andWhere([UserProfile::tableName() . '.' . $fieldName => null]);
        } else {
            $query->andFilterWhere([
                UserProfile::tableName() . '.' . $fieldName => $this->{$fieldName}
            ]);
        }
    }

    /**
     * This method add a query for a field that filter by a role
     * @param ActiveQuery $query
     * @param string $fieldName
     */
    protected function userProfileRolesQuery($query, $fieldName, $role) {
        if ((strlen($this->{$fieldName}) > 0) && (($this->{$fieldName} == 0) || ($this->{$fieldName} == 1))) {
            $operator = ($this->{$fieldName} == 0 ? 'not in' : 'in');
            $userIds = \Yii::$app->getAuthManager()->getUserIdsByRole($role);
            $query->andWhere([$operator, UserProfile::tableName() . '.user_id', $userIds]);
        }
    }

    /**
     * @param ActiveDataProvider $dataProvider
     */
    protected function setUserProfileSort($dataProvider) {
        // Check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort([
                'attributes' => [
                    'user_profile.nome' => [
                        'asc' => ['nome' => SORT_ASC, 'cognome' => SORT_ASC],
                        'desc' => ['nome' => SORT_DESC, 'cognome' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'user_profile.cognome' => [
                        'asc' => ['cognome' => SORT_ASC, 'nome' => SORT_ASC],
                        'desc' => ['cognome' => SORT_DESC, 'nome' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'surnameName' => [
                        'asc' => ['cognome' => SORT_ASC, 'nome' => SORT_ASC],
                        'desc' => ['cognome' => SORT_DESC, 'nome' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'prevalentPartnership' => [
                        'asc' => ['organizations.name' => SORT_ASC, 'cognome' => SORT_ASC, 'nome' => SORT_ASC],
                        'desc' => ['organizations.name' => SORT_DESC, 'cognome' => SORT_DESC, 'nome' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'user_profile.created_at',
                    'user_profile.last_online'
                ],
                'defaultOrder' => [
                    $this->orderAttribute => (int) $this->orderType
                ]
            ]);
        }
    }

    /**
     * Search all active users.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = $this->baseSearch($params);
        $query->andWhere([UserProfile::tableName() . '.attivo' => 1]);

        if (isset($params['profileClasses'])) {
            $query->leftJoin(UserProfileClassesUserMm::tableName(), UserProfileClassesUserMm::tableName() . '.user_id = ' . User::tableName() . '.id');
            $query->andWhere(['IN', UserProfileClassesUserMm::tableName() . '.user_profile_classes_id', $params['profileClasses']]);
        }

        if (isset($params['endUsers'])) {
            $query->leftJoin(UserProfileClassesUserMm::tableName(), UserProfileClassesUserMm::tableName() . '.user_id = ' . User::tableName() . '.id');
            $query->andWhere(['or',
                [UserProfileClassesUserMm::tableName() . '.user_profile_classes_id' => null],
                ['NOT IN', UserProfileClassesUserMm::tableName() . '.user_profile_classes_id', $params['endUsers']]
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->setUserProfileSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * Search all active users that was validated at least once.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchOnceValidatedUsers($params, $pageSize = 20) {
        $query = $this->baseSearch($params);
        $query->andWhere([UserProfile::tableName() . '.attivo' => 1, UserProfile::tableName() . '.validato_almeno_una_volta' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->setUserProfileSort($dataProvider);

        if (is_int($pageSize)) {
            $pagination = $dataProvider->getPagination();
            if (!$pagination) {
                $pagination = new Pagination();
                $dataProvider->setPagination($pagination);
            }
            $pagination->setPageSize($pageSize);
        }


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * Search all active users that are a community manager for at least one community.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchCommunityManagerUsers($params) {
        $communityModule = Yii::$app->getModule('community');
        if (!is_null($communityModule)) {
            /** @var \open20\amos\community\AmosCommunity $communityModule */
            // Query to search all platform user that are community managers in at least one not closed community.
            $queryAll = $this->baseSearch($params);
            $queryAll->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
            $communityTableName = \open20\amos\community\models\Community::tableName();
            $communityUserMmTableName = \open20\amos\community\models\CommunityUserMm::tableName();
            $loggedUserId = Yii::$app->getUser()->getId();
            $queryAll->innerJoin($communityUserMmTableName,
                    UserProfile::tableName() . '.user_id = ' . $communityUserMmTableName . '.user_id');
            $queryAll->innerJoin($communityTableName,
                    $communityTableName . '.id = ' . $communityUserMmTableName . '.community_id');
            $queryAll->andWhere([
                $communityUserMmTableName . '.status' => \open20\amos\community\models\CommunityUserMm::STATUS_ACTIVE,
                $communityUserMmTableName . '.role' => \open20\amos\community\models\CommunityUserMm::ROLE_COMMUNITY_MANAGER,
            ]);
            $queryAll->andWhere([$communityUserMmTableName . '.deleted_at' => null]);
            $queryAll->andWhere([$communityTableName . '.deleted_at' => null]);
            $queryAll->andWhere(['<>', $communityTableName . '.community_type_id', \open20\amos\community\models\CommunityType::COMMUNITY_TYPE_CLOSED]);
            $queryAll->groupBy(UserProfile::tableName() . '.user_id');
            $allCommunityManagers = $queryAll->all();

            // Query to retrieve community managers with community id.
            $managerQuery = new Query();
            $managerQuery->select([
                $communityTableName . '.id',
                $communityTableName . '.community_type_id',
                $communityUserMmTableName . '.user_id',
                $communityUserMmTableName . '.role',
            ]);
            $managerQuery->from($communityTableName);
            $managerQuery->innerJoin($communityUserMmTableName,
                    $communityTableName . '.id = ' . $communityUserMmTableName . '.community_id');
            $managerQuery->andWhere([$communityUserMmTableName . '.status' => \open20\amos\community\models\CommunityUserMm::STATUS_ACTIVE]);
            $managerQuery->andWhere([$communityTableName . '.context' => \open20\amos\community\models\Community::className()]);
            $managerQuery->andWhere([$communityTableName . '.validated_once' => 1]);
            $managerQuery->andWhere([$communityUserMmTableName . '.deleted_at' => null]);
            $managerQuery->andWhere([$communityTableName . '.deleted_at' => null]);
            $managerQuery->andWhere(['<>', $communityTableName . '.community_type_id', \open20\amos\community\models\CommunityType::COMMUNITY_TYPE_CLOSED]);
            $communityUserMms = $managerQuery->all();

            $managerUserIds = [];
            foreach ($allCommunityManagers as $communityManager) {
                /** @var UserProfile $communityUserMm */
                $managerCommunityIds = [];
                foreach ($communityUserMms as $communityUserMm) {
                    if ($communityManager->user_id == $communityUserMm['user_id']) {
                        $managerCommunityIds[] = $communityUserMm['id'];
                        $managerUserIds[] = $communityManager->user_id;
                    }
                }

                // This means that there's only closed communities
                if (!in_array($communityManager->user_id, $managerUserIds)) {
                    foreach ($communityUserMms as $communityUserMm) {
                        if (in_array($communityUserMm['id'], $managerCommunityIds) && ($communityUserMm['user_id'] == $loggedUserId)) {
                            $managerUserIds[] = $communityManager->user_id;
                        }
                    }
                }
            }
            $managerUserIds = array_unique($managerUserIds);

            /** @var ActiveQuery $query */
            $query = AmosAdmin::instance()->createModel('UserProfile')->find()->andWhere(['user_id' => $managerUserIds]);
            $query->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        } else {
            $query = $this->baseSearch($params);
            $query->where('0');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->setUserProfileSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    public static function isCommunityManagerOfAtLeastOne($userId = null) {
        if (\Yii::$app->user->isGuest || class_exists(\Yii::getAlias('@vendor/open20/amos-community/src/AmosCommunity')) && !empty(\Yii::$app->getModule(\open20\amos\community\AmosCommunity::getModuleName()))) {
            return 0;
        }
        if (empty($userId)) {
            $userId = \Yii::$app->user->id;
        }
        $count = \open20\amos\community\models\CommunityUserMm::find()
                ->andWhere(['user_id' => $userId])
                ->andWhere(['role' => \open20\amos\community\models\CommunityUserMm::ROLE_COMMUNITY_MANAGER])
                ->limit(1)
                ->count();
        return $count;
    }

    /**
     * Search all active users with "FACILITATOR" role.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchFacilitatorUsers($params) {
        $query = $this->baseSearch($params);
        $query->andWhere([UserProfile::tableName() . '.attivo' => 1]);
        $facilitatorUserIds = \Yii::$app->getAuthManager()->getUserIdsByRole('FACILITATOR');
        $query->andWhere(['in', UserProfile::tableName() . '.user_id', $facilitatorUserIds]);
        $query->andWhere(['!=', 'dont_show_facilitator', 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->setUserProfileSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * Search all active users with "FACILITATOR" role.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchMyNetwork($params) {
        $userId = \Yii::$app->user->id;
        $query = $this->baseSearch($params);
        $query->andWhere([UserProfile::tableName() . '.attivo' => 1]);

        $contacts = UserContact::find()->andWhere(['or',
                    ['user_id' => $userId],
                    ['contact_id' => $userId],
                ])
                ->andWhere(['status' => [UserContact::STATUS_ACCEPTED, UserContact::STATUS_INVITED]])
                ->select(new \yii\db\Expression("IF(user_id = $userId, contact_id, user_id) as id"));
        $query->andWhere(['in', UserProfile::tableName() . '.user_id', $contacts]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $this->setUserProfileSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * Search all inactive users.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchInactiveUsers($params) {
        $query = $this->baseSearch($params);
        $query->andWhere([UserProfile::tableName() . '.attivo' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->setUserProfileSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * This method count
     * @return int
     */
    public function getNewProfilesCount() {
        /** @var User $loggedUser */
        $loggedUser = Yii::$app->user->identity;
        /** @var UserProfile $loggedUserProfile */
        $loggedUserProfile = $loggedUser->getProfile();

        $query = new Query();
        $query
                ->from(UserProfile::tableName())
                ->andWhere(['>=', 'created_at', $loggedUserProfile->ultimo_logout]);

        return $query->count();
    }

    /**
     * Search all validated documents
     *
     * @param array $searchParamsArray Array of search words
     * @param int|null $pageSize
     * @return ActiveDataProvider
     */
    public function globalSearch($searchParamsArray, $pageSize = 5) {
        $dataProvider = $this->search([]);
        $pagination = $dataProvider->getPagination();
        if (!$pagination) {
            $pagination = new Pagination();
            $dataProvider->setPagination($pagination);
        }
        $pagination->setPageSize($pageSize);

        foreach ($searchParamsArray as $searchString) {
            $orQueries = [
                'or',
                ['like', self::tableName() . '.nome', $searchString],
                ['like', self::tableName() . '.cognome', $searchString],
                ['like', self::tableName() . '.presentazione_breve', $searchString],
                ['like', self::tableName() . '.presentazione_personale', $searchString],
                ['like', self::tableName() . '.note', $searchString],
                ['like', 'user.email', $searchString],
            ];

            $dataProvider->query->andWhere($orQueries);
        }

        // you can't search user for tags, so if select a tag for search i don't show anything
        $tagsValues = \Yii::$app->request->get('tagValues');
        if (!empty($tagsValues)) {
            $dataProvider->query->andWhere(0);
        }


        $searchModels = [];
        foreach ($dataProvider->models as $m) {
            array_push($searchModels, $this->convertToSearchResult($m));
        }
        $dataProvider->setModels($searchModels);

        return $dataProvider;
    }

    /**
     * @param object $model The model to convert into SearchResult
     * @return SearchResult
     */
    public function convertToSearchResult($model) {
        $searchResult = new SearchResult();
        $searchResult->url = $model->getFullViewUrl();
        $searchResult->box_type = "image";
        $searchResult->id = $model->id;
        $searchResult->titolo = $model->nome . " " . $model->cognome;
        $searchResult->abstract = $model->presentazione_breve;
        if (!empty($model->getUserProfileImage())) {
            $searchResult->immagine = $model->userProfileImage;
        } else {
            $imageUrl = "/img/defaultProfilo.png";
            if ($model->sesso == 'Maschio') {
                $imageUrl = "/img/defaultProfiloM.png";
            } elseif ($model->sesso == 'Femmina') {
                $imageUrl = "/img/defaultProfiloF.png";
            }
            $searchResult->immagine = $imageUrl;
        }

        return $searchResult;
    }

    public function baseUsersQuery($params) {

//        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
                ->andWhere(['user_profile.validato_almeno_una_volta' => 1]);

        return $query;
    }

    /**
     * Search method useful to retrieve news to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearch($params, $limit = null) {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $query = $this->baseUsersQuery($params);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'cognome' => SORT_ASC,
                    'nome' => SORT_ASC,
                ],
            ],
        ]);

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return " . $command . ";"));
            }
        }

        return $dataProvider;
    }

    /**
     * @return array
     */
    public function cmsViewFields() {
        $viewFields = [];

//    array_push($viewFields, new CmsField("titolo", "TEXT", 'amosnews', $this->attributeLabels()["titolo"]));
//    array_push($viewFields, new CmsField("descrizione_breve", "TEXT", 'amosnews', $this->attributeLabels()['descrizione_breve']));
//    array_push($viewFields, new CmsField("newsImage", "IMAGE", 'amosnews', $this->attributeLabels()['newsImage']));
//    array_push($viewFields, new CmsField("data_pubblicazione", "DATE", 'amosnews', $this->attributeLabels()['data_pubblicazione']));

        $viewFields[] = new CmsField("nome", "TEXT", 'amosadmin', $this->attributeLabels()["nome"]);
        $viewFields[] = new CmsField("cognome", "TEXT", 'amosadmin', $this->attributeLabels()['descrizione_breve']);
        $viewFields[] = new CmsField("userProfileImage", "IMAGE", 'amosadmin',
                $this->attributeLabels()['userProfileImage']);
        $viewFields[] = new CmsField("created_at", "DATE", 'amosadmin', $this->attributeLabels()['created_at']);

        return $viewFields;
    }

    /**
     * @return array
     */
    public function cmsSearchFields() {
        $searchFields = [];

        $searchFields[] = new CmsField("nome", "TEXT");
        $searchFields[] = new CmsField("cognome", "TEXT");

        return $searchFields;
    }

    /**
     * @param int $id
     * @return boolean
     */
    public function cmsIsVisible($id) {
        $retValue = false;

        if (isset($id)) {
            $md = $this->findOne($id);
            if (!is_null($md)) {
                $retValue = $md->validato_almeno_una_volta;
            }
        }

        return $retValue;
    }

}
