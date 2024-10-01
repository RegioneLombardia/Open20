<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\models\search
 * @category   CategoryName
 */

namespace open2\amos\ticket\models\search;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\OrganizationsModuleInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\core\record\Record;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketCategorie;
use open2\amos\ticket\models\TicketCategorieUsersMm;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class TicketSearch
 * TicketSearch represents the model behind the search form about `open2\amos\ticket\models\Ticket`.
 * @package open2\amos\ticket\models\search
 */
class TicketSearch extends Ticket implements SearchModelInterface, ContentModelSearchInterface, CmsModelInterface
{
    public $general;
    public $statusSearch;
    public $stringCreatedBy;

    /**
     * @var AmosTicket|null $ticketModule
     */
    protected $ticketModule;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->ticketModule = AmosTicket::instance();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'id',
                'closed_by',
                'ticket_categoria_id',
                'version',
                'created_by',
                'updated_by',
                'deleted_by'
                ], 'integer'],
            [[
                'general',
                'titolo',
                'status',
                'statusSearch',
                'dossier_id',
                'phone',
                'closed_at',
                'created_at',
                'updated_at',
                'deleted_at',
                'stringCreatedBy'
                ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
                [
                'stringCreatedBy' => AmosTicket::t('amosticket', 'Creato da')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getScope($params)
    {
        $scope = $this->formName();
        if (!isset($params[$scope])) {
            $scope = '';
        }
        return $scope;
    }

    /**
     * @param ActiveQuery $query
     * @return mixed
     */
    public function applySearchFilters($query)
    {
        $query->andFilterWhere([
            static::tableName().'.id' => $this->id,
            static::tableName().'.closed_by' => $this->closed_by,
            static::tableName().'.closed_at' => $this->closed_at,
            static::tableName().'.ticket_categoria_id' => $this->ticket_categoria_id,
            static::tableName().'.version' => $this->version,
            static::tableName().'.created_at' => $this->created_at,
            static::tableName().'.updated_at' => $this->updated_at,
            static::tableName().'.deleted_at' => $this->deleted_at,
            static::tableName().'.created_by' => $this->created_by,
            static::tableName().'.updated_by' => $this->updated_by,
            static::tableName().'.deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', static::tableName().'.titolo', $this->titolo])
            ->andFilterWhere(['like', static::tableName().'.status', $this->statusSearch]);
        $query->andFilterWhere(['like', static::tableName().'.phone', $this->phone]);
        $query->andFilterWhere(['like', static::tableName().'.dossier_id', $this->dossier_id]);

        $query->andFilterWhere(['OR',
            ['like', static::tableName().'.titolo', $this->general],
            ['like', static::tableName().'.descrizione', $this->general],
            ['like', static::tableName().'.descrizione_breve', $this->general],
            ['like', static::tableName().'.forward_message', $this->general],
            ['like', static::tableName().'.partnership_type', $this->general],
        ]);

        if (!empty($this->stringCreatedBy)) {

            $query->join('LEFT JOIN', 'user_profile profile', 'profile.user_id = '.static::tableName().'.created_by');
            $expr = new Expression('
            IF('.static::tableName().'.created_by,
                CONCAT(profile.nome, \' \', profile.cognome) COLLATE utf8_general_ci,
                CONCAT('.static::tableName().'.guest_name, \' \', '.static::tableName().'.guest_surname) COLLATE utf8_general_ci
                )
            LIKE
            \'%'.$this->stringCreatedBy.'%\'
       
            ');


            $query->andWhere($expr);
        }

        return $query;
    }

    /**
     * Method that searches all tickets waiting.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchAllTicket($params, $limit = null)
    {
        return $this->search($params, "all", $limit);
    }

    /**
     * Method that searches all tickets waiting.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchTicketWaiting($params, $limit = null)
    {
        return $this->search($params, "all", $limit, Ticket::TICKET_WORKFLOW_STATUS_WAITING);
    }

    /**
     * Method that searches all tickets closed.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchTicketClosed($params, $limit = null)
    {
        return $this->search($params, "all", $limit, Ticket::TICKET_WORKFLOW_STATUS_CLOSED);
    }

    /**
     * Method that searches all tickets processing.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchTicketProcessing($params, $limit = null)
    {
        if ($this->ticketModule->enableAdministrativeTicketCategory) {
            $statuses = [Ticket::TICKET_WORKFLOW_STATUS_PROCESSING, Ticket::TICKET_WORKFLOW_STATUS_WAITING_TECHNICAL_ASSISTANCE];
        } else {
            $statuses = Ticket::TICKET_WORKFLOW_STATUS_PROCESSING;
        }
        return $this->search($params, "all", $limit, $statuses);
    }

    /**
     * Content search method
     *
     * @param array $params
     * @param string $queryType
     * @param int|null $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params, $queryType = null, $limit = null, $onlyStatus = false)
    {
        // $defaultOrder = ['order' => SORT_ASC];
        //$queryOrder = ((!is_null($order) && is_array($order) && isset($order['order']) && is_numeric($order['order'])) ? $order : $defaultOrder);

        if (!empty($queryType)) {
            if ($queryType == 'all') {
                if (Yii::$app->getUser()->can('AMMINISTRATORE_TICKET')) {

                } else if (Yii::$app->getUser()->can('REFERENTE_TICKET')) {
                    $queryType = 'menaged-by';
                } else {
                    $queryType = 'created-by';
                }
            }

            $query = $this->buildQuery($params, $queryType, $onlyStatus);
        } else {
            $query = $this->baseSearch($params);
        }

        $query->joinWith('ticketCategoria');

        $abilita_per_community = false;

        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh = \Yii::$app->getModule('cwh');

        if (!is_null($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
            // If scope set, filter categories for cwh
            if (!empty($scope) && isset($scope['community'])) {

                $abilita_per_community = true;

                $query->andFilterWhere([
                    TicketCategorie::tableName().'.community_id' => $scope['community'],
                ]);
            }
        }

        $query->andFilterWhere([
            TicketCategorie::tableName().'.abilita_per_community' => $abilita_per_community,
        ]);

        $query->limit($limit);

        $dp_params = ['query' => $query,];
        if ($limit) {
            $dp_params ['pagination'] = false;
        }
        //set the data provider
        $dataProvider = new ActiveDataProvider($dp_params);
        $dataProvider = $this->searchDefaultOrder($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->applySearchFilters($query);

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function searchDefaultOrder($dataProvider)
    {
        // Check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort($this->createOrderClause());
        } else {
            $dataProvider->setSort([
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]);
        }
        return $dataProvider;
    }

    /**
     * Content Model
     * @param array $params
     * @param string $queryType
     * @return ActiveQuery $query
     * @throws \yii\base\InvalidConfigException
     */
    public function buildQuery($params, $queryType, $onlyStatus = false)
    {
        $query          = $this->baseSearch($params);
        $classname      = \open2\amos\ticket\models\Ticket::className();
        $moduleCwh      = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;

        $isSetCwh = !is_null($moduleCwh) && in_array($classname, $moduleCwh->modelsEnabled);

        if ($isSetCwh) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery(
                $classname, [
                'queryBase' => $query
            ]);
        }

        switch ($queryType) {
            case 'created-by':
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhOwn();
                } else {
                    $query->andFilterWhere([
                        static::tableName().'.created_by' => Yii::$app->getUser()->id
                    ]);
                }
                if ($onlyStatus) {
                    $query->andWhere([
                        static::tableName().'.status' => $onlyStatus
                    ]);
                }
                break;
            case 'menaged-by':
                /* if ($isSetCwh) {

                  } else { */
                $userProfileId = 0;
                $userProfile   = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
                if (!empty($userProfile)) {
                    $userProfileId = $userProfile->id;
                }
                $query->innerJoin(TicketCategorieUsersMm::tableName(),
                    'ticket.ticket_categoria_id = '.TicketCategorieUsersMm::tableName().'.ticket_categoria_id'
                    .' AND '.TicketCategorieUsersMm::tableName().'.user_profile_id = '.$userProfileId);
                //}
                //}
                if ($onlyStatus) {
                    $query->andWhere([
                        static::tableName().'.status' => $onlyStatus
                    ]);
                }
                break;
            case 'all':
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhAll();
                } else {
                    if ($onlyStatus) {
                        $tmp = [
                            static::tableName().'.status' => $onlyStatus
                        ];
                        $query->andWhere([
                            static::tableName().'.status' => $onlyStatus
                        ]);
                    }
                }
                break;
        }
        return $query;
    }

    /**
     * Content base search: all content matching search parameters and not deleted.
     *
     * @param array $params Search parameters
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);
        /** @var Record $className */
        $className = \open2\amos\ticket\models\Ticket::className();
        return $className::find()->distinct();
    }

    /**
     * @inheritdoc
     */
    public function cmsIsVisible($id)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function cmsSearch($params, $limit)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $query  = $this->buildQuery($params, 'all');
        $this->applySearchFilters($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return ".$command.";"));
            }
        }
        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function cmsSearchFields()
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("titolo", "TEXT"));
        array_push($searchFields, new CmsField("descrizione", "TEXT"));

        return $searchFields;
    }

    /**
     * @inheritdoc
     */
    public function cmsViewFields()
    {
        $viewFields = [];
        //$this->attributeLabels()["titolo"];
        array_push($viewFields, new CmsField("titolo", "TEXT", 'amosticket', $this->attributeLabels()["titolo"]));
        array_push($viewFields,
            new CmsField("descrizione", "TEXT", 'amosticket', $this->attributeLabels()['descrizione']));
        return $viewFields;
    }

    /**
     * @inheritdoc
     */
    public function globalSearch($searchParamsArray, $pageSize)
    {
        return [
            'titolo',
            'descrizione',
        ];
    }

    /**
     * @inheritdoc
     */
    public function searchAllQuery($params)
    {
        return $this->buildQuery($params, 'all');
    }

    /**
     * @inheritdoc
     */
    public function searchCreatedByMeQuery($params)
    {
        return $this->buildQuery($params, 'created-by');
    }

    /**
     * @inheritdoc
     */
    public function searchOwnInterestsQuery($params)
    {
        return $this->buildQuery($params, 'own-interest');
    }

    /**
     * @inheritdoc
     */
    public function searchToValidateQuery($params)
    {
        return $this->buildQuery($params, 'to-validate');
    }

    /**
     * @inheritdoc
     */
    public function convertToSearchResult($model)
    {
        
    }

    /**
     * @return Query
     */
    public function queryExtractTicket()
    {
        $query = new Query();

        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        $moduleOrg = \Yii::$app->getModule($adminModule->getOrganizationModuleName());
        if (!empty($moduleOrg)) {
            /** @var OrganizationsModuleInterface $orgModelClassName */
            $orgModelClassName = $moduleOrg->getOrganizationModelClass();
            /** @var Record|OrganizationsModuleInterface $orgModelOcj */
            $orgModelOcj = Yii::createObject($orgModelClassName);
            $tableName = $orgModelOcj::tableName();
            $query->select(new Expression(" t.id     AS 'ticket_id'
          , c.titolo AS 'categoria'
          , t.created_at AS 'created_at'
          , concat(p.nome, ' ', p.cognome) AS 'operatore_creatore'
          , u.email as 'email_operatore'
          , t.closed_at AS 'closed_at'
          , t.titolo as 'titolo'
          , t.status as 'status'
          , ExtractValue(t.descrizione, '//text()') as 'descrizione'
          , ExtractValue(group_concat(comment_text separator ' #****#'), '//text()') as 'commenti'
          , ExtractValue(group_concat(comment_reply_text separator '#****#'), '//text()') as 'risposte_ai_commenti'
          , o.name AS 'societa_afferente'"))
                ->from('ticket as t')
                ->innerJoin('ticket_categorie c', 't.ticket_categoria_id = c.id')
                ->innerJoin('user u', 't.created_by = u.id')
                ->innerJoin('user_profile p', 'p.user_id = u.id')
                ->leftJoin('comment cm',
                    "t.id = cm.context_id and cm.context = 'open2\\\\amos\\\\ticket\\\\models\\\\Ticket'")
                ->leftJoin('comment_reply cr', 'cm.id = cr.comment_id')
                ->leftJoin("$tableName o", 'p.prevalent_partnership_id = o.id')
                ->groupBy('t.id');
        } else {
            $query->select(new Expression(" t.id     AS 'ticket_id'
          , c.titolo AS 'categoria'
          , t.created_at AS 'created_at'
          , concat(p.nome, ' ', p.cognome) AS 'operatore_creatore'
          , u.email as 'email_operatore'
          , t.closed_at AS 'closed_at'
          , t.titolo as 'titolo'
          , t.status as 'status'
          , ExtractValue(t.descrizione, '//text()') as 'descrizione'
          , ExtractValue(group_concat(comment_text separator ' #****#'), '//text()') as 'commenti'
          , ExtractValue(group_concat(comment_reply_text separator '#****#'), '//text()') as 'risposte_ai_commenti'"))
                ->from('ticket as t')
                ->innerJoin('ticket_categorie c', 't.ticket_categoria_id = c.id')
                ->innerJoin('user u', 't.created_by = u.id')
                ->innerJoin('user_profile p', 'p.user_id = u.id')
                ->leftJoin('comment cm',
                    "t.id = cm.context_id and cm.context = 'open2\\\\amos\\\\ticket\\\\models\\\\Ticket'")
                ->leftJoin('comment_reply cr', 'cm.id = cr.comment_id')
                ->groupBy('t.id');
        }



        // If scope set, filter categories for cwh
        $isCommunity = false;
        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh   = \Yii::$app->getModule('cwh');

        if (!is_null($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
            if (!empty($scope) && isset($scope['community'])) {
                $isCommunity = true;
                $query->andWhere([
                    'c.community_id' => $scope['community'],
                ]);
            }
        }

        if (!$isCommunity) {
            $query->andWhere([
                'c.community_id' => null,
            ]);
        }

        return $query;
    }
}