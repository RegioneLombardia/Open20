<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\models\search
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\models\search;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class ExpressionsOfInterestSearch
 * ExpressionsOfInterestSearch represents the model behind the search form about `open20\amos\partnershipprofiles\models\ExpressionsOfInterest`.
 * @package open20\amos\partnershipprofiles\models\search
 */
class ExpressionsOfInterestSearch extends ExpressionsOfInterest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'id',
                'partnership_profile_id',
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [[
                'status',
                'partnership_offered',
                'additional_information',
                'clarifications',
                'created_at',
                'updated_at',
                'deleted_at'
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * This is the base search.
     * @param array $params
     * @return ActiveQuery
     */
    public function baseSearch($params)
    {
        /** @var ExpressionsOfInterest $expressionsOfInterestModel */
        $expressionsOfInterestModel = $this->partnerProfModule->createModel('ExpressionsOfInterest');

        /** @var ActiveQuery $query */
        $query = $expressionsOfInterestModel::find();

        $query->joinWith('partnershipProfile', true, 'INNER JOIN');

        // Init the default search values
        $this->initOrderVars();

        // Check params to get orders value
        $this->setOrderVars($params);

        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    public function baseFilter($query)
    {
        $query->andFilterWhere([
            self::tableName() . '.partnership_profile_id' => $this->partnership_profile_id,
            self::tableName() . '.created_at' => $this->created_at,
            self::tableName() . '.updated_at' => $this->updated_at,
            self::tableName() . '.deleted_at' => $this->deleted_at,
            self::tableName() . '.created_by' => $this->created_by,
            self::tableName() . '.updated_by' => $this->updated_by,
            self::tableName() . '.deleted_by' => $this->deleted_by
        ]);

        $query->andFilterWhere(['like', self::tableName() . '.partnership_offered', $this->partnership_offered])
            ->andFilterWhere(['like', self::tableName() . '.additional_information', $this->additional_information])
            ->andFilterWhere(['like', self::tableName() . '.clarifications', $this->clarifications]);

        return $query;
    }

    /**
     * @param ActiveDataProvider $dataProvider
     */
    protected function setSearchSort($dataProvider)
    {
//        // Check if can use the custom module order
//        if ($this->canUseModuleOrder()) {
//            $dataProvider->setSort([
//                'attributes' => [
//                    'status' => [
//                        'asc' => [self::tableName() . '.status' => SORT_ASC],
//                        'desc' => [self::tableName() . '.status' => SORT_DESC]
//                    ],
//                    'partnershipProfile.title' => [
//                        'asc' => [PartnershipProfiles::tableName() . '.title' => SORT_ASC],
//                        'desc' => [PartnershipProfiles::tableName() . '.title' => SORT_DESC]
//                    ]
//                ]
//            ]);
//        }

        // DISABLE EXPRESSIONS OF INTEREST ORDER BY ADD A FIELD HIDDEN IN THE LISTS
        $dataProvider->setSort([
            'attributes' => ['additional_information']
        ]);
    }

    /**
     * Generic search on all records.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->baseSearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSearchSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * Search all records for ADMIN.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAllAdmin($params)
    {
        $query = $this->baseSearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSearchSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * Search all records for users.
     * @param array $params
     * @return ActiveQuery
     */
    public function searchAllQuery($params)
    {
        $query = $this->baseSearch($params);
        $query->andWhere(['!=', self::tableName() . '.status', self::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT]);
        return $query;
    }

    /**
     * Search all records for users.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAll($params)
    {
        $query = $this->searchAllQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSearchSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * Search all records for users.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAllAuthor($params)
    {
        $query = $this->searchAllQuery($params);
        $query->andWhere([self::tableName() . '.created_by' => \Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSearchSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * Search all records for users.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAllAuthorFacilitator($params)
    {
        $query = $this->searchAllQuery($params);
        /** @var User $loggedUser */
        $loggedUser = \Yii::$app->user->identity;
        /** @var UserProfile $loggedUserProfile */
        $loggedUserProfile = $loggedUser->userProfile;
        $query->innerJoin(UserProfile::tableName(), self::tableName() . '.created_by = ' . UserProfile::tableName() . '.user_id');
        $query->andWhere([UserProfile::tableName() . '.facilitatore_id' => $loggedUserProfile->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSearchSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function searchCreatedByQuery($params)
    {
        $query = $this->baseSearch($params);
        $query->andWhere([self::tableName() . '.created_by' => \Yii::$app->user->getId()]);
        return $query;
    }

    /**
     * Search all records for users.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchCreatedBy($params)
    {
        $query = $this->searchCreatedByQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSearchSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function searchReceivedQuery($params)
    {
        $query = $this->baseSearch($params);
        $query->andWhere([PartnershipProfiles::tableName() . '.created_by' => \Yii::$app->user->getId()]);
        return $query;
    }

    /**
     * Search all records for users.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchReceived($params)
    {
        $query = $this->searchReceivedQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSearchSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * Search for facilitator.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchForFacilitator($params)
    {
        /** @var User $loggedUser */
        $loggedUser = \Yii::$app->user->identity;
        $usersQuery = new Query();
        $usersQuery->select(['user_id']);
        $usersQuery->from(UserProfile::tableName());
        $usersQuery->where(['deleted_at' => null, 'facilitatore_id' => $loggedUser->userProfile->id]);
        $userIdsFacilitatedByLoggedUser = $usersQuery->column();

        $query = $this->baseSearch($params);
        $query->andWhere([self::tableName() . '.created_by' => $userIdsFacilitatedByLoggedUser]);
        $query->andWhere(['<>', self::tableName() . '.status', ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSearchSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }
}
