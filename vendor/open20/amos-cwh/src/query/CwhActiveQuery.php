<?php

namespace open20\amos\cwh\query;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\record\CachedActiveQuery;
use open20\amos\core\user\User;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\exceptions\CwhException;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhNodi;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use open20\amos\tag\models\TagModelsAuthItemsMm;
use open20\amos\tag\utility\TagUtility;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class CwhActiveQuery
 * @package open20\amos\cwh\query
 */
class CwhActiveQuery extends ActiveQuery
{
    /** Id of Publication rule for 'All users' */
    const RULE_PUBLIC = 1;

    /** Id of Publication rule for 'all user with matching tag interest' */
    const RULE_TAG = 2;

    /** Id of Publication rule for 'all user based in the network scope' */
    const RULE_NETWORK = 3;

    /** Id of Publication rule for 'all user based in the network scope and matching tag interest' */
    const RULE_NETWORK_TAG = 4;

    /**
     * @var string $modelClass The modelClass of content to search
     */
    public $modelClass;

    /**
     * @var string $tableName The table name of content to search (eg news, documents, ..)
     */
    public $tableName;

    /**
     * @var ActiveQuery $queryBase the initial query to search for content without added cwh conditions
     */
    public $queryBase;

    /*
     * @var boolean bypassscope to search for content without added scope
     */
    public $bypassScope = false;
    public $cwhConfigContentsId;

    /**
     * @var ActiveRecord modelObject
     */
    public $modelObject;

    /** @var AmosCwh $moduleCwh */
    public $moduleCwh;

    /** @var  bool $moduleTagEnabled - true if module tag AmosTag is present and enabled in platform modules */
    private $moduleTagEnabled;

    /**
     * @var string $status The required worklflow status of the content to search
     */
    public $status;

    /**
     * @var int $userId - if null the logged user id is considered
     */
    public static $userId;

    /**
     * @var $userProfile
     */
    public static $userProfile;

    /**
     * @var $networkModels - cwh network model configured
     */
    private static $networkModels;

    /**
     * @var null|int|array $networkIds - customized id/ array ids of networks for filter by scope Query
     */
    public $networkIds = null;

    /**
     * @var bool $validByScopeIgnoreStatus
     */
    public $validByScopeIgnoreStatus = false;

    /**
     * @var bool $validByScopeIgnoreDates
     */
    public $validByScopeIgnoreDates = false;

    /**
     * @inheritdoc
     * @throws InvalidConfigException, CwhException
     */
    public function init()
    {
        parent::init();
        $refClass = new \ReflectionClass($this->modelClass);

        if (!$refClass->isSubclassOf(ActiveRecord::className())) {
            throw new InvalidConfigException(AmosCwh::t('amoscwh',
                'Impossibile applicare filtraggi a {$modelClass}, in quanto non è un contenuto'),
                [
                    'modelClass' => $this->modelClass
                ]);
        }
        $this->modelObject = Yii::createObject($this->modelClass);
        $this->moduleCwh = Yii::$app->getModule('cwh');
        $moduleTag = Yii::$app->getModule('tag');
        $this->moduleTagEnabled = isset($moduleTag);

        if (!isset($this->tableName)) {
            $this->tableName = $this->modelObject->tableName();
        }
        if (!isset($this->cwhConfigContentsId)) {
            $configContent = CwhConfigContents::findOne(['tablename' => $this->tableName]);
            if (is_null($configContent)) {
                $message = AmosCwh::t('amoscwh', '#cwh_exception_content_type_not_configured') . ' ' . $this->tableName;
                $message .= '. ' . AmosCwh::t('amoscwh', '#configure') . ' /cwh/configuration/wizard';
                throw new CwhException($message);
            }
            $this->cwhConfigContentsId = $configContent->id;
        }

        if (empty($this->getUserId())) {
//            $user = Yii::$app->getUser();
            $user = Yii::$app->user;
            if (!is_null($user) && !$user->isGuest) {
                $this->setUserId($user->getId());
            }
        }
    }

    /**
     * @param $userId
     */
    public function setUserId($userId)
    {
        if ($userId != self::$userId) {
            self::$userProfile = null;
        }

        self::$userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return self::$userId;
    }

    /**
     * @param $userprofile
     */
    public function setUserProfile($userprofile)
    {
        self::$userProfile = $userprofile;
    }

    /**
     * @return mixed
     */
    public function getUserProfile()
    {
        if (get_class(\Yii::$app) == 'open20\amos\core\applications\ConsoleApplication' && !empty(\Yii::$app->user) && !empty(\Yii::$app->user->id)) {
            $this->setUserId(\Yii::$app->user->id);
            self::$userProfile = null;
        }
        if (empty(self::$userProfile)) {
            $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
            if (!is_null($adminModule)) {
                $usermodel = $adminModule->model('UserProfile');
                $queryCache = CachedActiveQuery::instance($usermodel::find()->andWhere(['user_id' => $this->getUserId()]));
                $queryCache->cache(60);
                self::$userProfile = $queryCache->one();
            }
        }
        return self::$userProfile;
    }

    /**
     * @param ActiveQuery $query
     */
    public function getPublicationJoin($query)
    {
        $query->innerJoin('cwh_pubblicazioni',
            'cwh_pubblicazioni.content_id = ' . $this->tableName . '.id AND cwh_pubblicazioni.cwh_config_contents_id = ' . $this->cwhConfigContentsId);
    }

    /**
     * @return $this
     */
    public function joinPublication()
    {
        $this->innerJoin('cwh_pubblicazioni',
            'cwh_pubblicazioni.content_id = ' . $this->tableName . '.id AND cwh_pubblicazioni.cwh_config_contents_id = ' . $this->cwhConfigContentsId);
        return $this;
    }

    /**
     * Search specific content type objects in status to validate and visible by logged user
     *
     * @param bool $checkStatus
     * @return ActiveQuery $query
     */
    public function getQueryCwhToValidate($checkStatus = true)
    {

        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());

        if ($cwhModule->cached) {
            $query = CachedActiveQuery::instance($this->queryBase);
            $query->cache($cwhModule->cacheDuration);
        } else {
            $query = (clone $this->queryBase);
        }

//        $isValidator = \Yii::$app->getUser()->can( $this->modelObject->getValidatorRole());
        $isValidator = \Yii::$app->user->can($this->modelObject->getValidatorRole());

        if ($isValidator == false) {
            //The user has permission to validate the content
            if ($cwhModule->cached) {
                $queryUser = CachedActiveQuery::instance($this->queryBase);
                $queryUser->cache($cwhModule->cacheDuration);
            } else {
                $queryUser = (clone $this->queryBase);
            }
            $this->getPublicationJoin($queryUser);
            $queryUser->innerJoin('cwh_pubblicazioni_cwh_nodi_validatori_mm',
                'cwh_pubblicazioni_cwh_nodi_validatori_mm.cwh_pubblicazioni_id = cwh_pubblicazioni.id');
            $queryUser->innerJoin('cwh_auth_assignment',
                'cwh_auth_assignment.user_id = ' . $this->getUserId() . ' AND ' .
                'cwh_auth_assignment.cwh_network_id = cwh_pubblicazioni_cwh_nodi_validatori_mm.cwh_network_id' . ' AND ' .
                'cwh_auth_assignment.cwh_config_id = cwh_pubblicazioni_cwh_nodi_validatori_mm.cwh_config_id'
            );
            $queryUser->andWhere(['is', 'cwh_auth_assignment.deleted_at', null]);
            $queryUser->andWhere([
                'cwh_auth_assignment.item_name' => "CWH_PERMISSION_VALIDATE_" . $this->modelClass,
            ]);
            $queryUser->select($this->tableName . '.id');

            $query->andWhere([$this->tableName . '.id' => $queryUser]);


            if ($cwhModule->cached) {
                $queryFacilitator = CachedActiveQuery::instance($this->queryBase);
                $queryFacilitator->cache($cwhModule->cacheDuration);
            } else {
                $queryFacilitator = (clone $this->queryBase);
            }

            $userProfile = $this->getUserProfile();
            $cwhConfigUser = CwhConfig::findOne(['tablename' => 'user']);
            $this->getPublicationJoin($queryFacilitator);
            $queryFacilitator
                ->innerJoin('cwh_pubblicazioni_cwh_nodi_validatori_mm',
                    'cwh_pubblicazioni_cwh_nodi_validatori_mm.cwh_pubblicazioni_id = cwh_pubblicazioni.id AND ' .
                    'cwh_pubblicazioni_cwh_nodi_validatori_mm.cwh_config_id = ' . $cwhConfigUser->id)
                ->innerJoin('user_profile',
                    'user_profile.user_id = cwh_pubblicazioni_cwh_nodi_validatori_mm.cwh_network_id')
                ->andWhere(
                    'user_profile.facilitatore_id =' . $userProfile->id
                );

            $queryFacilitator->select($this->tableName . '.id');
            $query->orWhere([$this->tableName . '.id' => $queryFacilitator]);
        }
        if ($checkStatus) {
            if ($this->modelObject->getBehavior('workflow') != null) {

                $this->status = $this->modelObject->getToValidateStatus();
                $query->andWhere([$this->tableName . '.status' => $this->status]);
            }
        }

        $this->filterByScopeValidation($query);

        return $query;
    }

    /**
     * Search specific content type objects in status validated and visible by logged user (based on publication rule and visibility)
     *
     * @return ActiveQuery $query
     */
    public function getQueryCwhOwnInterest()
    {
        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        if ($cwhModule->cached) {
            $query = CachedActiveQuery::instance($this->queryBase);
            $query->cache($cwhModule->cacheDuration);
        } else {
            $query = (clone $this->queryBase);
        }

        $userProfileId = $this->getUserProfile()->id;
        $userHasTags = CwhTagOwnerInterestMm::find()->andWhere(['record_id' => $userProfileId])->exists();

        $tag = false;
        $network = false;
        $networkTag = false;

        // Contenuti pubblicati verso tutti equivale ad un contenuto con 
        // tutti i tag e quindi va in own-interest
        $queryAllUsers = $this->getQueryAllUsers([self::RULE_PUBLIC]);
        $queryAllUsers->select($this->tableName . '.id');


        if ($this->moduleTagEnabled && $userHasTags) {
            $tag = true;
            $queryTag = $this->getUserTagQuery([self::RULE_TAG]);
            $queryTag->select($this->tableName . '.id');
        }

        $queryUserNetwork = $this->getUserNetworkQuery([self::RULE_NETWORK]);
        if ($queryUserNetwork != null) {
            $network = true;
            $queryUserNetwork->select($this->tableName . '.id');
        }

        if ($this->moduleTagEnabled && $userHasTags) {
            $queryUserNetworkAndTag = $this->getUserNetworkAndTagQuery([self::RULE_NETWORK_TAG]);
            if ($queryUserNetworkAndTag != null) {
                $networkTag = true;
                $queryUserNetworkAndTag->select($this->tableName . '.id');
            }
        }

        if ($tag && $network && $networkTag) {
            $query->andWhere(['or',
                [$this->tableName . '.id' => $queryTag],
                [$this->tableName . '.id' => $queryUserNetwork],
                [$this->tableName . '.id' => $queryUserNetworkAndTag],
                [$this->tableName . '.id' => $queryAllUsers],
            ]);
        } else if ($tag && $network) {
            $query->andWhere(['or',
                [$this->tableName . '.id' => $queryTag],
                [$this->tableName . '.id' => $queryUserNetwork],
                [$this->tableName . '.id' => $queryAllUsers],
            ]);
        } else if ($tag && $networkTag) {
            $query->andWhere(['or',
                [$this->tableName . '.id' => $queryTag],
                [$this->tableName . '.id' => $queryUserNetworkAndTag],
                [$this->tableName . '.id' => $queryAllUsers],
            ]);
        } else if ($network && $networkTag) {
            $query->andWhere(['or',
                [$this->tableName . '.id' => $queryUserNetwork],
                [$this->tableName . '.id' => $queryUserNetworkAndTag],
                [$this->tableName . '.id' => $queryAllUsers],
            ]);
        } else if ($tag) {
            $query->andWhere(['or',
                [$this->tableName . '.id' => $queryTag],
                [$this->tableName . '.id' => $queryAllUsers],
            ]);
        } else if ($network) {
            $query->andWhere(['or',
                [$this->tableName . '.id' => $queryUserNetwork],
                [$this->tableName . '.id' => $queryAllUsers],
            ]);
        } else if ($networkTag) {
            $query->andWhere(['or',
                [$this->tableName . '.id' => $queryUserNetworkAndTag],
                [$this->tableName . '.id' => $queryAllUsers],
            ]);
        }

        $this->filterValidatedByScope($query);

        return $query;
    }

    /**
     * Search specific content type objects in status validated
     *
     * @return ActiveQuery $query
     */
    public function getQueryCwhAll($cwhConfigId = null, $cwhNetworkId = null, $checkUser = false)
    {

        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        if ($cwhModule->cached) {
            $query = CachedActiveQuery::instance($this->queryBase);
            $query->cache($cwhModule->cacheDuration);
        } else {
            $query = (clone $this->queryBase);
        }

        $this->getPublicationJoin($query);

        if (isset($cwhConfigId)) {
            // We already know the network type for editors (cwh config id)
            $query
                ->innerJoin('cwh_pubblicazioni_cwh_nodi_editori_mm',
                    "cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_pubblicazioni_id = cwh_pubblicazioni.id")
                ->andWhere([
                    'cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_config_id' => $cwhConfigId
                ]);

            if (isset($cwhNetworkId)) {
                //search contents for specified nwtwork editor
                $query->andWhere(['cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_network_id' => $cwhNetworkId]);
            }
            // visbility query does just check if a network is visibile generally, not for single user (in user network mm table)
            $query = $this->getVisibilityQuery($query, null, $checkUser);
        } else {
            // check editor visibility for logged user
            $query
                ->leftJoin('cwh_pubblicazioni_cwh_nodi_editori_mm',
                    "cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_pubblicazioni_id = cwh_pubblicazioni.id")
                ->leftJoin(CwhNodi::tableName() . ' qca',
                    'cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_config_id = qca.cwh_config_id AND cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_network_id = qca.record_id');
            $query = $this->getVisibilityQuery($query, null, $checkUser, 'qca');
        }

        $this->filterValidatedByScope($query);

        return $query;
    }

    /**
     * Filter contents by a status validated and by a specific set of entities if the scope filter was setted (eg. filter by organization, community,..)
     *
     * @param ActiveQuery $query The query to obtain contents still not filtered by status ans scope
     */
    public function filterValidatedByScope($query)
    {
        if (!$this->validByScopeIgnoreStatus) {
            if ($this->modelObject->getBehavior('workflow') != null) {
                $this->status = $this->modelObject->getCwhValidationStatuses();
                $query->andWhere([$this->tableName . '.status' => $this->status]);
            }
        }

        if (!$this->validByScopeIgnoreDates) {
            if (interface_exists('open20\amos\core\interfaces\PublicationDateFieldsInterface') && ($this->modelObject instanceof \open20\amos\core\interfaces\PublicationDateFieldsInterface)) {
                $dataOdierna = ($this->modelObject->theDatesAreDatetime() ? date('Y-m-d H:i:s') : date('Y-m-d'));
                $dataPubblicazioneField = $this->modelObject->getPublicatedFromField();
                $dataRimozioneField = $this->modelObject->getPublicatedAtField();
                if (!empty($dataPubblicazioneField)) {
                    $query->andWhere("ISNULL(" . $dataPubblicazioneField . ") OR " . $dataPubblicazioneField . " <= '" . $dataOdierna . "'");
                }
                if (!empty($dataRimozioneField)) {
                    $query->andWhere("ISNULL(" . $dataRimozioneField . ") OR " . $dataRimozioneField . " >= '" . $dataOdierna . "'");
                }
            } else {
                $dataOdierna = date('Y-m-d');
                $table = Yii::$app->db->schema->getTableSchema($this->tableName);
                if (isset($table->columns['data_pubblicazione'])) {
                    $query->andWhere("ISNULL(" . $this->tableName . ".data_pubblicazione) OR " . $this->tableName . ".data_pubblicazione <= '" . $dataOdierna . "'");
                }
                if (isset($table->columns['data_rimozione'])) {
                    $query->andWhere("ISNULL(" . $this->tableName . ".data_rimozione) OR " . $this->tableName . ".data_rimozione >= '" . $dataOdierna . "'");
                }
            }
        }

        $this->filterByScope($query);
    }

    /**
     * Search specific content type objects created by the logged user and in status draft
     *
     * @return ActiveQuery $query
     */
    public function getQueryCwhDraft()
    {

        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        if ($cwhModule->cached) {
            $query = CachedActiveQuery::instance($this->queryBase);
            $query->cache($cwhModule->cacheDuration);
        } else {
            $query = (clone $this->queryBase);
        }
        if ($this->modelObject->getBehavior('workflow') != null) {

            $this->status = $this->modelObject->getDraftStatus();
            $query->andWhere([$this->tableName . '.status' => $this->status]);
        }

        $query->andFilterWhere([
            $this->tableName . '.created_by' => $this->getUserId()
        ]);

        $this->filterByScope($query);
        return $query;
    }

    /**
     * Search specific content type objects created by the logged user
     *
     * @return ActiveQuery $query
     */
    public function getQueryCwhOwn()
    {

        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        if ($cwhModule->cached) {
            $query = CachedActiveQuery::instance($this->queryBase);
            $query->cache($cwhModule->cacheDuration);
        } else {
            $query = (clone $this->queryBase);
        }
        $query->andFilterWhere([
            $this->tableName . '.created_by' => $this->getUserId()
        ]);

        $this->filterByScope($query);
        return $query;
    }

    /**
     * Filter contents by a specific set of entities if the scope filter was setted (eg. filter by organization, community,..)
     *
     * @param ActiveQuery $query The query to obtain contents still not filtered by scope
     */
    public function filterByScope($query)
    {
        if (!empty($this->moduleCwh->scope) && !$this->bypassScope) {
            foreach ($this->moduleCwh->scope as $key => $scopeCondition) {
                $cwhConfigId = CwhConfig::findOne(['tablename' => $key])->id;
                $query->innerJoin('cwh_pubblicazioni publications',
                    'publications.content_id = ' . $this->tableName . '.id AND publications.cwh_config_contents_id = ' . $this->cwhConfigContentsId);
                if (is_null($this->networkIds)) {
                    $query
                        ->innerJoin('cwh_pubblicazioni_cwh_nodi_editori_mm editors_mm_' . $key,
                            'editors_mm_' . $key . '.cwh_config_id = ' . $cwhConfigId . ' AND editors_mm_' . $key . '.cwh_network_id = ' . $scopeCondition . ' AND editors_mm_' . $key . '.cwh_pubblicazioni_id = publications.id ')
                        ->andWhere([
                            'editors_mm_' . $key . '.deleted_at' => null,
                        ]);
                } else {
                    $query
                        ->innerJoin('cwh_pubblicazioni_cwh_nodi_editori_mm editors_mm_' . $key,
                            'editors_mm_' . $key . '.cwh_config_id = ' . $cwhConfigId . ' AND editors_mm_' . $key . '.cwh_pubblicazioni_id = publications.id ')
                        ->andWhere([
                            'editors_mm_' . $key . '.cwh_network_id' => $this->networkIds,
                            'editors_mm_' . $key . '.deleted_at' => null,
                        ]);
                }
            }
        }
    }

    public function getBypassScope()
    {
        return $this->bypassScope;
    }

    public function setBypassScope($bypassScope)
    {
        $this->bypassScope = $bypassScope;
    }

    /**
     * Filter contents by a specific set of validation entities if the scope filter was setted (eg. filter by organization, community,..)
     *
     * @param ActiveQuery $query The query to obtain contents still not filtered by scope
     */
    public function filterByScopeValidation($query)
    {
        if (!empty($this->moduleCwh->scope) && !$this->bypassScope) {
            foreach ($this->moduleCwh->scope as $key => $scopeCondition) {
                $cwhConfigId = CwhConfig::findOne(['tablename' => $key])->id;
                $query
                    ->innerJoin('cwh_pubblicazioni publications',
                        'publications.content_id = ' . $this->tableName . '.id AND publications.cwh_config_contents_id = ' . $this->cwhConfigContentsId)
                    ->innerJoin('cwh_pubblicazioni_cwh_nodi_validatori_mm validators_mm',
                        'validators_mm.cwh_config_id = ' . $cwhConfigId . ' AND validators_mm.cwh_network_id = ' . $scopeCondition .
                        ' AND validators_mm.cwh_pubblicazioni_id = publications.id ')
                    ->andWhere([
                        'validators_mm.deleted_at' => null,
                    ]);
            }
        }
    }

    /**
     * Create the query fetching contents when the logged user interests match at least one tag of that content
     *
     * @param array $publicationRules The list of publication rules for which content type filter by tags has to be selected
     * @return ActiveQuery $query
     */
    public function getQueryAllUsers($publicationRules)
    {
        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        if ($cwhModule->cached) {
            $query = CachedActiveQuery::instance($this->queryBase);
            $query->cache($cwhModule->cacheDuration);
        } else {
            $query = (clone $this->queryBase);
        }
        $this->getPublicationJoin($query);
        $query
            ->andWhere([
                'cwh_pubblicazioni.cwh_regole_pubblicazione_id' => $publicationRules,
            ]);
        return $query;
    }

    /**
     * Create the query fetching contents when the logged user interests match (publication rule = 2)
     *
     * @param array $publicationRules The list of publication rules for which content type filter by tags has to be selected
     * @return ActiveQuery $query
     */
    public function getUserTagQuery($publicationRules)
    {
        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        if ($cwhModule->cached) {
            $query = CachedActiveQuery::instance($this->queryBase);
            $query->cache($cwhModule->cacheDuration);
        } else {
            $query = (clone $this->queryBase);
        }

        $this->getPublicationJoin($query);
        $this->getTagMatchQuery($query);
        $query->andWhere(['cwh_pubblicazioni.cwh_regole_pubblicazione_id' => $publicationRules]);
        return $query;
    }

    /**
     * Create the query fetching contents when the logged user interests match:
     * - tagsMatchEachTree = false : at least one tag of that content
     * - tagsMatchEachTree = true : at least one tag of that content for each tree enabled
     *
     * @param ActiveQuery $query
     * @return ActiveQuery $query
     */
    public function getTagMatchQuery($query)
    {
        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        $userProfileId = $this->getUserProfile()->id;

        $cwhTagOwnerInterestMmTable = CwhTagOwnerInterestMm::tableName();
        $tagModelsAuthItemsMmTable = TagModelsAuthItemsMm::tableName();
        $entityTagsMmTable = EntitysTagsMm::tableName();

        if (!$cwhModule->tagsMatchEachTree) {

            $query->innerJoin($entityTagsMmTable, $entityTagsMmTable . ".record_id = " . $this->tableName . ".id")
                ->innerJoin($cwhTagOwnerInterestMmTable,
                    $entityTagsMmTable . '.tag_id = ' . $cwhTagOwnerInterestMmTable . '.tag_id AND ' . $cwhTagOwnerInterestMmTable . '.record_id = ' . $userProfileId)
                ->andWhere([
                    $entityTagsMmTable . '.classname' => $this->modelClass,
                    $cwhTagOwnerInterestMmTable . '.record_id' => $userProfileId,
                ])->andWhere([$cwhTagOwnerInterestMmTable . '.deleted_at' => null])
                ->andWhere([$entityTagsMmTable . '.deleted_at' => null]);
        } else {

            $allRootTagModelsAuthItemsMmTable = TagModelsAuthItemsMm::find()->andWhere(['classname' => $this->modelClass])->select('tag_id')->distinct();
            $allRootCwhTagOwnerInterestMmTable = CwhTagOwnerInterestMm::find()->andWhere(['record_id' => $userProfileId])->select('root_id')->distinct();
            $rootIds = Tag::find()
                ->andWhere(['in', 'root', $allRootTagModelsAuthItemsMmTable])
                ->andWhere(['in', 'root', $allRootCwhTagOwnerInterestMmTable])
                ->select('root')->groupBy('root')->column();
            foreach ($rootIds as $rootId) {
                $tableTagsMmAlias = 'tag_mm_' . $rootId;
                $tableUserTagsMmAlias = 'user_tag_mm_' . $rootId;
                $query
                    ->innerJoin('entitys_tags_mm ' . $tableTagsMmAlias,
                        $tableTagsMmAlias . ".record_id = " . $this->tableName . ".id and " . $tableTagsMmAlias . '.root_id = ' . $rootId)
                    ->innerJoin('' . $cwhTagOwnerInterestMmTable . ' ' . $tableUserTagsMmAlias,
                        $tableTagsMmAlias . '.tag_id = ' . $tableUserTagsMmAlias . '.tag_id AND ' . $tableUserTagsMmAlias . '.record_id = ' . $userProfileId
                        . ' AND ' . $tableUserTagsMmAlias . '.root_id = ' . $rootId)
                    ->andWhere([
                        $tableTagsMmAlias . '.classname' => $this->modelClass,
                        $tableUserTagsMmAlias . '.record_id' => $userProfileId,
                    ])->andWhere([$tableUserTagsMmAlias . '.deleted_at' => null])
                    ->andWhere([$tableTagsMmAlias . '.deleted_at' => null]);
            }
        }

        return $query;
    }

    /**
     * Create the query fetching contents published in a network scope when the logged user belong to the network
     *
     * @param array $publicationRules The list of publication rules for which content type filter by network has to be selected
     * @return ActiveQuery $query
     */
    public function getUserNetworkQuery($publicationRules = null)
    {
        if (!self::$networkModels) {
            self::$networkModels = CwhConfig::find()->andWhere(['<>', 'tablename', 'user'])->all();
        }

        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        if ($cwhModule->cached) {
            $query = CachedActiveQuery::instance($this->queryBase);
            $query->cache($cwhModule->cacheDuration);
        } else {
            $query = (clone $this->queryBase);
        }
        $this->getPublicationJoin($query);
        $query->innerJoin('cwh_pubblicazioni_cwh_nodi_editori_mm',
            "cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_pubblicazioni_id = cwh_pubblicazioni.id");

        foreach (self::$networkModels as $networkModel) {
            $networkConfigId = $networkModel->id;
            $networkObject = Yii::createObject($networkModel->classname);
            $query->leftJoin($networkObject->getMmTableName(),
                $networkObject->getMmTableName() . '.' . $networkObject->getMmUserIdFieldName() . '=' . $this->getUserId()
                . " AND " . $networkObject->getMmTableName() . '.' . $networkObject->getMmNetworkIdFieldName() . '= cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_network_id'
                . " AND cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_config_id = " . $networkConfigId)
                ->andWhere($networkObject->getMmTableName() . '.deleted_at IS NULL');
            $mmTable = Yii::$app->db->schema->getTableSchema($networkObject->getMmTableName());
            if (isset($mmTable->columns['status'])) {
                $query->andWhere("ISNULL(" . $networkObject->getMmTableName() . ".status) OR " . $networkObject->getMmTableName() . ".status = 'ACTIVE'");
            }
            $query->andWhere(['or',
                ['and',
                    ['not', [$networkObject->getMmTableName() . "." . $networkObject->getMmNetworkIdFieldName() => null]],
                    ['cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_config_id' => $networkConfigId]
                ],
                ['not', ['cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_config_id' => $networkConfigId]]
            ]);
//            $query->andWhere(['or',
//
//                ['cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_config_id' => $networkConfigId]
//                ,
//                ['not', ['cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_config_id' => $networkConfigId ]]
//            ]);
        }
        if (isset($publicationRules)) {
            $query->andWhere([
                'cwh_pubblicazioni.cwh_regole_pubblicazione_id' => $publicationRules,
            ]);
        }
        $query->andWhere([
            'cwh_pubblicazioni_cwh_nodi_editori_mm.deleted_at' => null,
        ]);

        return $query;
    }

    /**
     * Create the query fetching contents published in a network scope when the logged user belong to the network and has matching tags
     *
     * @param array $publicationRules The list of publication rules for which content type filter by network has to be selected
     * @return ActiveQuery $query
     */
    public function getUserNetworkAndTagQuery($publicationRules)
    {
        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        if ($cwhModule->cached) {
            $query = CachedActiveQuery::instance($this->getUserNetworkQuery($publicationRules));
            $query->cache($cwhModule->cacheDuration);
        } else {
            $query = (clone $this->getUserNetworkQuery($publicationRules));
        }

        $this->getTagMatchQuery($query);

        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @param null|array $publicationRules
     * @param bool|true $checkUser - if network is not visible by default, if true check if the logged user can view the network
     * @param string|null $alias - in to merge backend/fronted need it
     * @return ActiveQuery $query
     */
    public function getVisibilityQuery($query, $publicationRules = null, $checkUser = true, $alias = null)
    {

        if (!is_null($this->getUserId()) && $checkUser) {
            $queryUserNetwork = $this->getUserNetworkQuery($publicationRules);
            if ($queryUserNetwork != null) {
                $queryUserNetwork->select($this->tableName . '.id');
                $query->andWhere([
                    'or',
                    ['or', [(empty($alias) ? CwhNodi::tableName() : "$alias") . '.visibility' => 1], (empty($alias) ? CwhNodi::tableName()
                            : "$alias") . '.visibility IS NULL'],
                    [
                        'and',
                        [(empty($alias) ? CwhNodi::tableName() : "$alias") . '.visibility' => 0],
                        [$this->tableName . '.id' => $queryUserNetwork]
                    ]
                ]);
            }
        } else {
            $query->leftJoin(CwhNodi::tableName() . ' vsbq',
                'cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_config_id = vsbq.cwh_config_id AND cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_network_id = vsbq.record_id');
            $query->andWhere(['or',
                ['vsbq.visibility' => 1],
                ['vsbq.visibility' => null]
            ]);
        }
        return $query;
    }

    public function getRecipients($publicationRule, $tagValues = [], $scopes = [])
    {
        $query = User::find();
        switch ($publicationRule) {
            case self::RULE_PUBLIC :
                break;
            case self::RULE_TAG:
                $query = $this->getRecipientsTag($query, $tagValues);
                break;
            case self::RULE_NETWORK:
                $query = $this->getRecipientsNetwork($query, $scopes);
                break;
            case self::RULE_NETWORK_TAG:
                $query = $this->getRecipientsNetwork($this->getRecipientsTag($query, $tagValues), $scopes);
                break;
        }
        return $query;
    }

    /**
     * @param $query
     * @param $tagValues
     * @return $this
     */
    public function getRecipientsTag($query, $tagValues)
    {
        if (is_array($tagValues)) {
            $tags = $tagValues;
        } else {
            $tags = explode(',', $tagValues);
        }

        /** @var UserProfile $userProfileModel */
        $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
        $userProfileTable = $userProfileModel::tableName();
        $userTable = User::tableName();
        $cwhTagOwnerInterestMmTable = CwhTagOwnerInterestMm::tableName();

        $cwhModule = \Yii::$app->getModule(AmosCwh::getModuleName());
        if ($cwhModule->cached) {
            $cahcehdQuery = CachedActiveQuery::instance($query);
            $cahcehdQuery->cache($cwhModule->cacheDuration);
        } else {
            $cahcehdQuery = (clone $query);
        }
        $cahcehdQuery->innerJoin($userProfileTable, $userTable . '.id = ' . $userProfileTable . '.user_id');

        if (!$cwhModule->tagsMatchEachTree) {
            $cahcehdQuery->innerJoin($cwhTagOwnerInterestMmTable,
                $cwhTagOwnerInterestMmTable . ".record_id = " . $userProfileTable . ".id AND " . $cwhTagOwnerInterestMmTable . ".interest_classname = :class ",
                [':class' => 'simple-choice',])
                ->andWhere(['in', $cwhTagOwnerInterestMmTable . '.tag_id', $tags])
                ->andWhere([$cwhTagOwnerInterestMmTable . '.deleted_at' => null]);
        } else {
            $rootIds = TagUtility::findAllRootTagIds();
            foreach ($rootIds as $rootId) {
                $tableTagsMmAlias = 'tag_mm_' . $rootId;
                $tableUserTagsMmAlias = 'user_tag_mm_' . $rootId;
                $cahcehdQuery
                    ->innerJoin($cwhTagOwnerInterestMmTable . ' ' . $tableUserTagsMmAlias,
                        $tableUserTagsMmAlias . '.root_id = ' . $rootId . ' AND ' .
                        $userProfileTable . '.user_id = ' . $tableUserTagsMmAlias . '.record_id')
                    ->innerJoin('entitys_tags_mm ' . $tableTagsMmAlias,
                        $tableTagsMmAlias . '.root_id = ' . $rootId . ' AND ' .
                        $tableTagsMmAlias . '.tag_id = ' . $tableUserTagsMmAlias . '.tag_id')
                    ->andWhere([$tableTagsMmAlias . '.classname' => $this->modelClass,])
                    ->andWhere(['in', $tableUserTagsMmAlias . '.tag_id', $tags])
                    ->andWhere([$tableUserTagsMmAlias . '.deleted_at' => null])
                    ->andWhere([$tableTagsMmAlias . '.deleted_at' => null]);
            }
            $cahcehdQuery->andWhere([$userProfileTable . '.deleted_at' => null]);
            $cahcehdQuery->andWhere([$userTable . '.deleted_at' => null]);
        }

        $cahcehdQuery->distinct();

        return $cahcehdQuery;
    }

    /**
     * @param $query
     * @param $scopes
     * @return mixed
     */
    public function getRecipientsNetwork($query, $scopes)
    {
        $networkModels = CwhConfig::find()->andWhere(['<>', 'tablename', 'user'])->all();
        $userIds = [];
        //TODO fix with cwh config id when it will be used instead of cwh nodi id
        foreach ($networkModels as $networkModel) {
            $networkIds = [];
            if (!empty($scopes)) {
                if (is_array($scopes)) {
                    foreach ($scopes as $scope) {
                        if (strstr($scope, $networkModel->tablename) !== false) {
                            $networkIds[] = substr($scope, strpos($scope, '-') + 1);
                        }
                    }
                } else {
                    if (is_string($scopes)) {
                        if (strstr($scopes, $networkModel->tablename) !== false) {
                            $networkIds[] = substr($scopes, strpos($scopes, '-') + 1);
                        }
                    }
                }
                if (!empty($networkIds)) {
                    $queryNetwork = (clone $query);
                    $networkObject = Yii::createObject($networkModel->classname);
                    $queryNetwork->innerJoin($networkObject->getMmTableName(),
                        $networkObject->getMmTableName() . '.' . $networkObject->getMmUserIdFieldName() . '=user.id')
                        ->andWhere($networkObject->getMmTableName() . '.deleted_at IS NULL')
                        ->andWhere([
                            'in',
                            $networkObject->getMmTableName() . '.' . $networkObject->getMmNetworkIdFieldName(),
                            $networkIds
                        ]);
                    $mmTable = Yii::$app->db->schema->getTableSchema($networkObject->getMmTableName());
                    if (isset($mmTable->columns['status'])) {
                        $queryNetwork->andWhere("ISNULL(" . $networkObject->getMmTableName() . ".status) OR " . $networkObject->getMmTableName() . ".status = 'ACTIVE'");
                    }
                    $queryNetwork->andWhere('user.deleted_at IS NULL');
                    $networkUserIds = $queryNetwork->select('user.id')->groupBy('user.id')->asArray()->column();
                    $userIds = ArrayHelper::merge($userIds, $networkUserIds);
                }
            }
        }
        if (!empty($userIds)) {
            $query->andWhere(['in', 'user.id', $userIds]);
        }
        return $query;
    }

    /**
     * Query for CwhNodi (networks) of which user is member.
     * @param integer $userId - if null logged user id is considered
     * @return mixed
     */
    public static function getUserNetworksQuery($userId = null)
    {
        if (is_null($userId)) {
            $userId = Yii::$app->user->id;
        }

        if (!self::$networkModels) {
            self::$networkModels = CwhConfig::find()->andWhere(['<>', 'tablename', 'user'])->all();
        }
        $cwhNodiTable = CwhNodi::tableName();
        $query = CwhNodi::find()->andWhere(['not like', $cwhNodiTable . '.id', 'user']);
        foreach (self::$networkModels as $networkModel) {
            $networkConfigId = $networkModel->id;
            $networkObject = Yii::createObject($networkModel->classname);
            $query->leftJoin($networkObject->getMmTableName(),
                $networkObject->getMmTableName() . '.' . $networkObject->getMmUserIdFieldName() . '=' . $userId
                . " AND " . $networkObject->getMmTableName() . '.' . $networkObject->getMmNetworkIdFieldName() . ' = ' . $cwhNodiTable . '.record_id')
                ->andWhere($networkObject->getMmTableName() . '.deleted_at IS NULL');
            $mmTable = Yii::$app->db->schema->getTableSchema($networkObject->getMmTableName());

            if (isset($mmTable->columns['status'])) {
                $condition1 = [0 => 'OR',
                    1 => [$networkObject->getMmTableName() . ".status" => null],
                    2 => [$networkObject->getMmTableName() . ".status" => 'ACTIVE']
                ];

                $condition1 = self::addConditionShowContentsPublicNetwork($condition1, true, $query, $networkObject, $cwhNodiTable);
                $query->andWhere($condition1);
            }

            $condition2 = [
                0 => 'or',
                1 => ['and',
                    ['not', [$networkObject->getMmTableName() . "." . $networkObject->getMmNetworkIdFieldName() => null]],
                    [$cwhNodiTable . '.cwh_config_id' => $networkConfigId]
                ],
                3 => ['not', [$cwhNodiTable . '.cwh_config_id' => $networkConfigId]]
            ];

            $condition2 = self::addConditionShowContentsPublicNetwork($condition2, false, $query, $networkObject, $cwhNodiTable);
            $query->andWhere($condition2);
        }

        return $query;
    }

    /**
     * @param $condition
     * @param $query
     * @param $networkObject
     * @param $cwhNodiTable
     * @return array|mixed
     */
    public static function addConditionShowContentsPublicNetwork($condition, $makeJoin, $query, $networkObject, $cwhNodiTable)
    {
        $module = AmosCwh::instance();
        if($module) {
            if ($module->showContensPublicNetwork && $networkObject::tableName() == 'community') {
                if ($makeJoin) {
                    $query->leftJoin($networkObject::tableName(), $networkObject::tableName() . '.id = ' . $cwhNodiTable . '.record_id');
                }
                $condition = ArrayHelper::merge($condition,
                    [4 => ['and',
                        ['not', [$networkObject::tableName() . ".id" => null]],
                        [$networkObject::tableName() . '.community_type_id' => 1]
                    ]]);
            }
        }
        return $condition;
    }

    /**
     * Query getting the contents published in a specific network scope.
     *
     * @param int $configId - network configuration id from CwhConfig
     * @param int $networkId - record id in the network table (eg. community->id)
     * @return $this
     */
    public function filterByPublicationNetwork($configId, $networkId)
    {

        $this->joinPublication();
        if (isset($configId)) {
            // We already know the network type for editors (cwh config id)
            $this
                ->innerJoin('cwh_pubblicazioni_cwh_nodi_editori_mm',
                    "cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_pubblicazioni_id = cwh_pubblicazioni.id")
                ->andWhere([
                    'cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_config_id' => $configId
                ]);
            if (isset($networkId)) {
                //search contents for specified nwtwork editor
                $this->andWhere(['cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_network_id' => $networkId]);
            }
            $this->andWhere([$this->tableName . '.deleted_at' => null]);
        }
        return $this;
    }
}