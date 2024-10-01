<?php

namespace open20\amos\collaborations\models\search;

use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\collaborations\models\CollaborationProposals;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * CollaborationExpressionsOfInterestSearch represents the model behind the search form about `open20\amos\collaborations\models\CollaborationExpressionsOfInterest`.
 *
 * @property-write mixed $sort
 */
class CollaborationExpressionsOfInterestSearch extends CollaborationExpressionsOfInterest
{

//    private $container;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    /**
     * Rules
     * @return array[]
     */
    public function rules()
    {
        return [
            [[
                'id', 'collaboration_proposal_id', 'created_by', 'updated_by', 'deleted_by'
            ], 'integer'],
            [[
                'proposal_title', 'proposal_id_een', 'proposer_first_name', 'proposer_last_name', 'proposer_email', 'company',
                'country', 'city', 'province', 'municipality', 'address', 'postal_code', 'created_at', 'updated_at',
                'deleted_at', 'proposal_category', 'proposer_full_name', 'proposer_phone', 'collaborationProposalStatus'
            ], 'safe'],
        ];
    }

    /**
     * Scenarios
     * @return array|array[]
     */
    public function scenarios()
    {
//         bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Base search
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        $query = CollaborationExpressionsOfInterest::find();

        // Ordinamento dal più recente al più vecchio
        if (!strpos(Url::current(), 'sort')) {
            $query->orderBy(['created_at' => SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('collaborationProposals');

        $this->setSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilters($query);
        $this->filterByFullName($query);
        $this->filterByStatus($query);

        return $dataProvider;
    }

    /**
     * Received expressions of interest for current user
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchReceived($params)
    {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['collaboration_proposals.created_by' => \Yii::$app->user->id]);

        return $dataProvider;
    }

    /**
     * Expressions of interest created by current user
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchCreatedByMe($params)
    {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['collaboration_expressions_of_interest.created_by' => \Yii::$app->user->id]);

        return $dataProvider;
    }

    /**
     * Received expressions of interest for proposal created by current user
     * @param $params
     * @param $proposalId
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchReceivedForOwnProposal($params, $proposalId)
    {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['collaboration_expressions_of_interest.collaboration_proposal_id' => $proposalId])
            ->andWhere(['collaboration_proposals.created_by' => \Yii::$app->user->id]);

        return $dataProvider;
    }

    /**
     * Set sort
     * @param $dataProvider
     * @return void
     */
    public function setSort($dataProvider)
    {
        $dataProvider->setSort([
            'attributes' => [
                'collaboration_proposal_id' => [
                    'asc' => ['collaboration_expressions_of_interest.collaboration_proposal_id' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.collaboration_proposal_id' => SORT_DESC],
                ],
                'proposal_title' => [
                    'asc' => ['collaboration_expressions_of_interest.proposal_title' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.proposal_title' => SORT_DESC],
                ],
                'proposal_id_een' => [
                    'asc' => ['collaboration_expressions_of_interest.proposal_id_een' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.proposal_id_een' => SORT_DESC],
                ],
                'proposer_first_name' => [
                    'asc' => ['collaboration_expressions_of_interest.proposer_first_name' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.proposer_first_name' => SORT_DESC],
                ],
                'proposer_last_name' => [
                    'asc' => ['collaboration_expressions_of_interest.proposer_last_name' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.proposer_last_name' => SORT_DESC],
                ],
                'proposer_email' => [
                    'asc' => ['collaboration_expressions_of_interest.proposer_email' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.proposer_email' => SORT_DESC],
                ],
                'proposer_phone' => [
                    'asc' => ['collaboration_expressions_of_interest.proposer_phone' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.proposer_phone' => SORT_DESC],
                ],
                'company' => [
                    'asc' => ['collaboration_expressions_of_interest.company' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.company' => SORT_DESC],
                ],
                'country' => [
                    'asc' => ['collaboration_expressions_of_interest.country' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.country' => SORT_DESC],
                ],
                'city' => [
                    'asc' => ['collaboration_expressions_of_interest.city' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.city' => SORT_DESC],
                ],
                'province' => [
                    'asc' => ['collaboration_expressions_of_interest.province' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.province' => SORT_DESC],
                ],
                'municipality' => [
                    'asc' => ['collaboration_expressions_of_interest.municipality' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.municipality' => SORT_DESC],
                ],
                'address' => [
                    'asc' => ['collaboration_expressions_of_interest.address' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.address' => SORT_DESC],
                ],
                'postal_code' => [
                    'asc' => ['collaboration_expressions_of_interest.postal_code' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.postal_code' => SORT_DESC],
                ],
                'proposal_category' => [
                    'asc' => ['collaboration_expressions_of_interest.proposal_category' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.proposal_category' => SORT_DESC],
                ],
                'proposer_full_name' => [
                    'asc' => ['collaboration_expressions_of_interest.proposer_full_name' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.proposer_full_name' => SORT_DESC],
                ],
                'created_at' => [
                    'asc' => ['collaboration_expressions_of_interest.created_at' => SORT_ASC],
                    'desc' => ['collaboration_expressions_of_interest.created_at' => SORT_DESC],
                ],
            ]]);
    }

    /**
     * Base filters
     * @param $query
     * @return void
     */
    public function baseFilters($query)
    {
        $query->andFilterWhere([
            'id' => $this->id,
            'collaboration_proposal_id' => $this->collaboration_proposal_id,
            'proposal_id_een' => $this->proposal_id_een,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'proposer_phone' => $this->proposer_phone,
            'updated_at' => $this->updated_at,
            'deleted_by' => $this->deleted_by,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'proposal_title', $this->proposal_title])
            //->andFilterWhere(['like', 'proposal_id_een', $this->proposal_id_een])
            ->andFilterWhere(['like', 'proposer_first_name', $this->proposer_first_name])
            ->andFilterWhere(['like', 'collaboration_expressions_of_interest.created_at', $this->created_at])
            ->andFilterWhere(['like', 'proposer_last_name', $this->proposer_last_name])
            ->andFilterWhere(['like', 'proposer_email', $this->proposer_email])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'municipality', $this->municipality])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['collaboration_proposals.category_id' => $this->proposal_category]);
        /*->andFilterWhere(['or',
            ['like', 'proposer_first_name', $this->proposer_full_name],
            ['like', 'proposer_last_name', $this->proposer_full_name]
        ])*/
    }

    /**
     * Filter by status workflow
     * @param $query
     * @return void
     */
    public function filterByStatus($query)
    {
        if ($this->collaborationProposalStatus == 'CollaborationProposalsWorkflow/ARCHIVED') {
            $query->andFilterWhere(['<', 'collaboration_proposals.end_date', date('Y-m-d')]);
        } else {
            $query->andFilterWhere(['like', 'collaboration_proposals.status', $this->collaborationProposalStatus])
                ->andFilterWhere(['>=', 'collaboration_proposals.end_date', date('Y-m-d')]);
        }
    }

    /**
     * RICERCA PER NOME COMPLETO (MANIFESTAZIONE EFFETTUATA DA)
     * @param $query
     * @return void
     */
    public function filterByFullName($query)
    {
        $query->andFilterWhere(['like', 'CONCAT(proposer_first_name," ",proposer_last_name)', $this->proposer_full_name]);
    }

}
