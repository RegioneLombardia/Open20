<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\models\search
 * @category   CategoryName
 */

namespace open20\amos\community\models\search;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\cwh\AmosCwh;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class CommunitySearch
 * CommunitySearch represents the model behind the search form about `open20\amos\community\models\Community`.
 * @package open20\amos\community\models\search
 */
class CommunitySearch extends Community implements SearchModelInterface, CmsModelInterface
{
    private $container;

    /** @var bool|false $subcommunityMode - true if navigating child communities of a main community */
    public
        $subcommunityMode = false,
        $isSearch         = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by', 'community_type_id'], 'integer'],
            [['status', 'name', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['logo_id', 'cover_image_id'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);

        $moduleCommunity = Yii::$app->getModule('community');

        /** @var Community $className */
        $className = $moduleCommunity->modelMap['Community'];
        return $className::find()->distinct();
    }

    /**
     * @inheritdoc
     */
    public function searchFieldsLike()
    {
        return [
            'name',
            'description',
        ];
    }

    /**
     * @inheritdoc
     */
    public function searchFieldsGlobalSearch()
    {
        return [
            'name',
            'description',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSearchQuery($query)
    {
        $communityModule = Yii::$app->getModule('community');
        if (!$communityModule->hideCommunityTypeSearchFilter) {
            $query->andFilterWhere(['community_type_id' => $this->community_type_id]);
        }
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchAdminAllCommunities($params, $limit = null)
    {
        return $this->search($params, 'admin-all', $limit);
    }

    /**
     * Search for communities whose the logged user belongs
     *
     * @param array $params $_GET search parameters array
     * @param int|null $limit
     * @param bool|false $onlyActiveStatus
     * @return ActiveDataProvider $dataProvider
     */
    public function searchMyCommunities($params, $limit = null, $onlyActiveStatus = false)
    {
        return $this->search($params, 'own-interest', $limit, $onlyActiveStatus);
    }

    /**
     * Method that searches all the news validated.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchMyCommunitiesWithTags($params, $limit = null)
    {
        return $this->search($params, "own-interest-with-tags", $limit);
    }

    /**
     * Search for the communities created by the logged user
     *
     * @param array $params $_GET search parameters array
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function searchCreatedByCommunities($params, $limit = null)
    {
        return $this->search($params, 'created-by', $limit);
    }

    /**
     * Search for the communities that the logged user has permission to validate
     *
     * @param array $params $_GET search parameters array
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function searchToValidateCommunities($params, $limit = null)
    {
        return $this->search($params, 'to-validate', $limit);
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function searchMyCommunitiesId()
    {
        $query = new \yii\db\Query;

        $communityUserMm = CommunityUserMm::find()
            ->select(['community_id'])
            ->andWhere(['user_id' => \Yii::$app->user->id]);

        return $communityUserMm;
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider|\yii\data\BaseDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchCommunitiesRecommended($params, $limit = null)
    {
        $dataProvider = $this->searchAdminAllCommunities($params);
        /** @var ActiveQuery $query */
        $query        = $dataProvider->query;

        $subqueryId = $this->searchMyCommunitiesId();

        $query->where(['not in', 'community.id', $subqueryId])->limit($limit)->all();
        $query->andWhere('community.deleted_at is null');
        $query->andWhere(['in', self::tableName().'.context', $this->communityModule->communityContextsToSearch]);
        $query->andWhere(['community.parent_id' => null]);
        $query->andWhere(['in', 'community.community_type_id', [CommunityType::COMMUNITY_TYPE_OPEN, CommunityType::COMMUNITY_TYPE_PRIVATE]]);

        if (!isset($params[$this->formName()]['tagValues'])) {
            $loggedProfile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();

            $listaTagId = \open20\amos\cwh\models\CwhTagOwnerInterestMm::findAll([
                    'classname' => 'open20\amos\admin\models\UserProfile',
                    'record_id' => $loggedProfile->id
            ]);

            if (!empty($listaTagId)) {
                $orQueries = null;
                $i         = 0;
                foreach ($listaTagId as $tag) {
                    if (!is_null($tag)) {
                        if ($i == 0) {
                            $query->innerJoin('entitys_tags_mm entities_tag',
                                "entities_tag.classname = '".addslashes($this->modelClassName)."' AND entities_tag.record_id=".static::tableName().".id");
                            $orQueries[] = 'or';
                        }
                        $orQueries[] = ['and', ["entities_tag.tag_id" => $tag->tag_id], ['entities_tag.root_id' => $tag->root_id],
                            ['entities_tag.deleted_at' => null]];
                        $i++;
                    }
                }
                if (!empty($orQueries)) {
                    $query->andWhere($orQueries);
                }
            }
        }

        $dp_params = ['query' => $query,];
        if ($limit) {
            $dp_params ['pagination'] = false;
        }

        $dataProvider = new ActiveDataProvider($dp_params);
        $dataProvider = $this->searchDefaultOrder($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function getVisibleNetworksQuery($query, $params = [], $onlyActiveStatus = true, $userId = null)
    {
        // Se c'è l'utente loggato le community aperte, riservate e quelle chiuse di cui l'utente fa parte.
        // Se non c'è un utente a cui fare riferimento si considerano solamente le community aperte.
        if (!is_null($userId)) {
            /** @var ActiveQuery $queryClosed */
            $queryClosed    = $this->baseSearch($params);
            $queryClosed->innerJoin(CommunityUserMm::tableName(),
                    'community.id = '.CommunityUserMm::tableName().'.community_id'
                    .' AND '.CommunityUserMm::tableName().'.user_id = '.$userId)
                ->andFilterWhere([
                    'community.community_type_id' => CommunityType::COMMUNITY_TYPE_CLOSED
                ])->andWhere(CommunityUserMm::tableName().'.deleted_at is null');
            $queryClosed->select('community.id');
            $queryNotClosed = $this->baseSearch($params);
            $queryNotClosed->leftJoin(CommunityUserMm::tableName(),
                    'community.parent_id = '.CommunityUserMm::tableName().'.community_id'
                    .' AND '.CommunityUserMm::tableName().'.user_id = '.$userId)
                ->andWhere(CommunityUserMm::tableName().'.deleted_at is null');
            $andWhere       = ['or',
                [self::tableName().'.parent_id' => null],
                ['and',
                    ['not', [self::tableName().'.parent_id' => null]],
                    ['not', [CommunityUserMm::tableName().'.community_id' => null]],
                ]
            ];
            if ($onlyActiveStatus) {
                $andWhere['or']['and'][] = [CommunityUserMm::tableName().'.status' => CommunityUserMm::STATUS_ACTIVE];
            }
            $queryNotClosed->andWhere($andWhere);
            $queryNotClosed->andWhere([
                'community.community_type_id' => [CommunityType::COMMUNITY_TYPE_OPEN, CommunityType::COMMUNITY_TYPE_PRIVATE]
            ]);
            $queryNotClosed->select('community.id');
            $query->andWhere(['community.id' => $queryClosed])->orWhere(['community.id' => $queryNotClosed]);
        } else {
            $query->andWhere([
                'community.community_type_id' => CommunityType::COMMUNITY_TYPE_OPEN
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function filterValidated($query)
    {
        $query->andWhere(['or',
            ['community.status' => Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED],
            ['and',
                ['community.validated_once' => 1],
                ['community.visible_on_edit' => 1]
            ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function filterByContext($query)
    {
        if (!empty($query)) {
            $query->andWhere(['in', self::tableName().'.context', $this->communityModule->communityContextsToSearch]);
            $query->andWhere([self::tableName().'.deleted_at' => null]);
        }
        /** @var AmosCommunity $moduleCommunity */
        $moduleCommunity     = Yii::$app->getModule('community');
        $showSubscommunities = $moduleCommunity->showSubcommunities;
        if ($this->subcommunityMode) {
            /** @var AmosCwh $moduleCwh */
            $moduleCwh = Yii::$app->getModule('cwh');
            if (!is_null($moduleCwh)) {
                $scope = $moduleCwh->getCwhScope();
                if (!empty($scope) && isset($scope[self::tableName()])) {
                    $communityId         = $scope[self::tableName()];
                    //filter by communities chlid of the community ID in cwh scope
                    $query->andWhere([self::tableName().'.parent_id' => $communityId]);
                    //and show subcommunities in the list anyway (we are in community dashboard)
                    $showSubscommunities = true;
                }
            }
        }
        if (!$showSubscommunities) {
            $query->andWhere([self::tableName().'.parent_id' => null]);
        }
    }

    /**
     * @inheritdoc
     */
    public function searchParticipants($params)
    {
        /** @var yii\db\ActiveQuery $query */
        $query                    = $this->getCommunityUserMms();
        $query->orderBy('user_profile.cognome ASC');
        $participantsDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $participantsDataProvider;
    }
    /*     * *
     * CmsModelInterface
     */

    /**
     * Search method useful to retrieve news to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearch($params, $limit = null)
    {
        $dataProvider = $this->search($params, $limit, 'all');
        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function cmsViewFields()
    {
        $viewFields = [];
        array_push($viewFields, new CmsField("name", "TEXT", 'amoscommunity', $this->attributeLabels()["name"]));
        array_push($viewFields,
            new CmsField("description", "TEXT", 'amoscommunity', $this->attributeLabels()['description']));
        array_push($viewFields,
            new CmsField("communityLogo", "IMAGE", 'amoscommunity', $this->attributeLabels()['communityLogo']));
        return $viewFields;
    }

    /**
     * @inheritdoc
     */
    public function cmsSearchFields()
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("name", "TEXT"));
        array_push($searchFields, new CmsField("description", "TEXT"));

        return $searchFields;
    }

    /**
     * @inheritdoc
     */
    public function cmsIsVisible($id)
    {
        $retValue = true;
        return $retValue;
    }

    /**
     * @param $query
     * @return array
     */
    public function searchCommunityTreeOrder($query, $params = null)
    {
        $availableCommunitiesIds = [];
        $orderBy                 = $this->getOrderStringSql();
        $query->orderBy($orderBy);
        foreach ($query->all() as $comunity) {
            $availableCommunitiesIds [] = $comunity->id;
        }

        $communities        = $query
            ->andWhere(['IS', 'parent_id', null])->all();
        $orderedCommunities = [];
        /** @var  $communityFather Community */
        foreach ($communities as $communityFather) {
            if (in_array($communityFather->id, $availableCommunitiesIds)) {
                $orderedCommunities [] = $communityFather;
            }
            $orderedCommunities = ArrayHelper::merge($orderedCommunities,
                    $this->recursiveGetSubcommunities($communityFather, $availableCommunitiesIds));
        }
        return $orderedCommunities;
    }

    /**
     * @param $communityFather
     * @param $availableCommunitiesIds
     * @return array
     */
    public function recursiveGetSubcommunities($communityFather, $availableCommunitiesIds)
    {
        $orderBy           = $this->getOrderStringSql();
        $communities       = $communityFather->getSubcommunities()->orderBy($orderBy)->all();
        $returnCommunities = [];
        if (count($communities) == 0) {
            return [];
        } else {
            foreach ($communities as $community) {
                if (in_array($community->id, $availableCommunitiesIds)) {
                    $returnCommunities[] = $community;
                }
                $community->level  = $communityFather->level + 1;
                $returnCommunities = ArrayHelper::merge($returnCommunities,
                        $this->recursiveGetSubcommunities($community, $availableCommunitiesIds));
            }

            return $returnCommunities;
        }
    }

    /**
     * @return string
     */
    public function getOrderStringSql()
    {
        $orderAttribute = !empty(\Yii::$app->request->get()['CommunitySearch']['orderAttribute']) ? \Yii::$app->request->get()['CommunitySearch']['orderAttribute']
                : '';
        $orderType      = !empty(\Yii::$app->request->get()['CommunitySearch']['orderType']) ? \Yii::$app->request->get()['CommunitySearch']['orderType']
                : '';
        if ($orderType == 4) {
            $orderType = 'ASC';
        } else if ($orderType == 3) {
            $orderType = 'DESC';
        }
        $orderBy = $orderAttribute.' '.$orderType;
        return $orderBy;
    }
}