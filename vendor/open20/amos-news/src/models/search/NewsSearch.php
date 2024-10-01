<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\models\search
 * @category   CategoryName
 */

namespace open20\amos\news\models\search;

use open20\amos\core\helpers\StringHelper;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use open20\amos\news\models\News;
use open20\amos\news\AmosNews;
use open20\amos\tag\models\EntitysTagsMm;
use kartik\datecontrol\DateControl;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ExpressionInterface;
use yii\di\Container;

/**
 * NewsSearch represents the model behind the search form about `open20\amos\news\models\News`.
 */
class NewsSearch extends News implements SearchModelInterface, ContentModelSearchInterface, CmsModelInterface
{
    /** @var  Container $container - used by ContentModel do not remove */
    private $container;

    public $keyword;

    /**
     * @inheritdoc
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        /** @var bool $isSearch - it is the content model search class */
        $this->isSearch = true;
        parent::__construct($config);

        $this->newsModule = AmosNews::getInstance();
        $this->modelClassName = $this->newsModule->model('News');
    }

    /**
     */
    public function rules()
    {
        return [
            [['id', 'primo_piano', 'hits', 'abilita_pubblicazione', 'news_categorie_id', 'created_by', 'updated_by', 'deleted_by',
                'version'], 'integer'],
            ['keyword', 'string'],
            [['titolo', 'sottotitolo', 'descrizione_breve', 'descrizione', 'metakey', 'metadesc', 'data_pubblicazione', 'data_rimozione',
                'created_at', 'updated_at', 'deleted_at', 'period', 'data_pubblicazione_from', 'data_pubblicazione_to'], 'safe'],
        ];
    }

    // Sovrascrivo l'after validate del base/news per togliere l'bbligatorietà dell e news fatta da STEFAN
    // sarebbe  il caso di cambiare quel tipo di oobligatorietà e fare  in altra maniera
    public function afterValidate()
    {
        // DO NOTING
    }

    /**
     * @return array|string[]
     */
    public function searchFieldsMatch()
    {
        return [
            'primo_piano',
            'hits',
            'abilita_pubblicazione',
            'news_categorie_id',
            'version',
        ];
    }

    /**
     * Array of fields to search with >= condition in search method
     *
     * @return array
     */
    public function searchFieldsGreaterEqual()
    {
        return [
            'data_pubblicazione'
        ];
    }

    /**
     * @return array|string[]
     */
    public function searchFieldsLike()
    {
        return [
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione',
            'metakey',
            'metadesc',
        ];
    }

    /**
     * @return array|string[]
     */
    public function searchFieldsGlobalSearch()
    {
        return [
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione',
            'metakey',
            'metadesc',
        ];
    }


    /**
     * ContentModel method to add custom search filters.
     * @param ActiveQuery $query
     * @return void
     */
    public function getSearchQuery($query)
    {
        if (!empty($this->period)) {
            if ($this->period == 'custom') {
                $query->andFilterWhere(['>=', 'data_pubblicazione', $this->data_pubblicazione_from]);
                $query->andFilterWhere(['<=', 'data_pubblicazione', $this->data_pubblicazione_to]);
            } else {
                $this->searchSelectPeriodFilter($query);
            }
        }
    }

    /**
     * Method that searches for news created by the logged user
     *
     * @param array $params
     * @param int $limit
     * @param boolean $only_drafts
     * @return ActiveDataProvider
     */
    public function searchOwnNews($params, $limit = null, $only_drafts = false)
    {
        return $this->search($params, "created-by", $limit, $only_drafts);
    }

    /**
     * Return $this.
     *
     * @return $this
     */
    public function validazioneAbilitata()
    {
        return $this;
    }

    /**
     * Method that searches all news to be validated.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchToValidateNews($params, $limit = null)
    {
        return $this->search($params, "to-validate", $limit);
    }

    /**
     * Method that search the latest research news validated, typically limit is $ 3.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function ultimeNews($params, $limit = null)
    {
        return $this->searchAll($params, $limit);
    }

    /**
     * Search method useful to retrieve all non-deleted news.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAll($params, $limit = null)
    {
        return $this->search($params, "all", $limit, false, 9);
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchAdminAll($params, $limit = null)
    {
        return $this->search($params, "admin-all", $limit, false, 9);
    }

    /**
     * Method that searches all the news validated.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchOwnInterest($params, $limit = null)
    {
        return $this->search($params, "own-interest", $limit, false, 9);
    }

    /**
     * @param $params
     * @param $limit
     * @return ActiveDataProvider
     */
    public function searchClone($params, $limit = null)
    {
        $dataProvider = $this->searchAdminAll($params, $limit);
        $cwhConfigContents = CwhConfigContents::findOne(['classname' => $this->modelClassName]);
        if ($cwhConfigContents) {
            $dataProvider->query->innerJoin('cwh_pubblicazioni', 'cwh_pubblicazioni.content_id = news.id');
            $dataProvider->query->andWhere(['cwh_pubblicazioni.cwh_config_contents_id' => $cwhConfigContents->id]);
            $dataProvider->query->andWhere(['cwh_pubblicazioni.cwh_regole_pubblicazione_id' => CwhRegolePubblicazione::ALL_USERS]);
        }
        $dataProvider->query->andWhere(['news.status' => News::NEWS_WORKFLOW_STATUS_VALIDATO]);


        return $dataProvider;
    }

    /**
     * Search method useful to retrieve validated news with both primo_piano and in_evidenza flags = true.
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHighlightedAndHomepageNews($params)
    {
        $query = $this->highlightedAndHomepageNewsQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function searchOtherPageNews($params, $limit = null)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);

        $query = $this->otherPageNewsQuery($params);

        if (\Yii::$app->user->isGuest) {
            $query->andWhere(['primo_piano' => 1]);
        }

        $this->applySearchFilters($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                    'created_at' => SORT_DESC,
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
                if (strpos($command, 'news_categorie_ids') !== false) {
                    $this->cmsFilterCategories($command, $query);
                } else {
                    $query->andWhere(eval("return " . $command . ";"));
                }
            }
        }

        return $dataProvider;
    }

    /**
     * Search method useful to retrieve validated news with primo_piano flag = true.
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHomepageNews($params)
    {
        $query = $this->homepageNewsQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        // TBD FRANZ - vero o non vero ritorna sempre e comunque
        // lo stesso $dataProvider a che serve allora?
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * get the query used by the related searchHighlightedAndHomepageNews method
     * return just the query in case data provider/query itself needs editing
     *
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    public function highlightedAndHomepageNewsQuery($params)
    {
        $now = date('Y-m-d H:i:s');
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->distinct()->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '" . str_replace('\\', '\\\\', News::className()) . "' and " . EntitysTagsMm::tableName() . ".record_id = " . News::tableName() . ".id  and " . EntitysTagsMm::tableName() . ".deleted_at is NULL")
            ->andWhere([
                $tableName . '.status' => News::NEWS_WORKFLOW_STATUS_VALIDATO,
                $tableName . '.in_evidenza' => 1,
                $tableName . '.primo_piano' => 1
            ])
            ->andWhere(['<=', 'data_pubblicazione', $now])
            ->andWhere(['or',
                    ['>=', 'data_rimozione', $now],
                    ['data_rimozione' => null]]
            )
            ->andWhere(['or',
                    ['>=', 'news_expiration_date', $now],
                    ['news_expiration_date' => null]]
            );

        return $query;
    }

    /**
     * get the query used by the related searchHomepageNews method
     * return just the query in case data provider/query itself needs editing
     *
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    public function otherPageNewsQuery($params)
    {
        $now = date('Y-m-d H:i:s');
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->distinct()->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '" . str_replace('\\', '\\\\', News::className()) . "' and " . EntitysTagsMm::tableName() . ".record_id = " . News::tableName() . ".id  and " . EntitysTagsMm::tableName() . ".deleted_at is NULL")
            ->andWhere([
                $tableName . '.status' => News::NEWS_WORKFLOW_STATUS_VALIDATO
                //rimossa condizione per filtro 'in_evidenza'
            ])
            ->andWhere(['<=', 'data_pubblicazione', $now])
            ->andWhere(['or',
                    ['>=', 'data_rimozione', $now],
                    ['data_rimozione' => null]]
            )
            ->andWhere(['or',
                    ['>=', 'news_expiration_date', $now],
                    ['news_expiration_date' => null]]
            );

        return $query;
    }

    /**
     * get the query used by the related searchHomepageNews method
     * return just the query in case data provider/query itself needs editing
     *
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    public function homepageNewsQuery($params)
    {
        $now = date('Y-m-d H:i:s');
        $tableName = $this->tableName();

        $query = $this->baseSearch($params)
            ->distinct()->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '" . str_replace('\\', '\\\\', News::className()) . "' and " . EntitysTagsMm::tableName() . ".record_id = " . News::tableName() . ".id  and " . EntitysTagsMm::tableName() . ".deleted_at is NULL")
            ->andWhere([
                $tableName . '.status' => News::NEWS_WORKFLOW_STATUS_VALIDATO,
                $tableName . '.primo_piano' => 1
            ])
            ->andWhere(['<=', 'data_pubblicazione', $now])
            ->andWhere(['or',
                    ['>=', 'data_rimozione', $now],
                    ['data_rimozione' => null]]
            )
            ->andWhere(['or',
                    ['>=', 'news_expiration_date', $now],
                    ['news_expiration_date' => null]]
            );

        return $query;
    }



    /**
     * Search method useful to retrieve news to show in frontend (with cms)
     * @param $params
     * @param null $limit
     * @param string $type
     * @return ActiveDataProvider
     */
    public function cmsSearch($params, $limit = null, $type = 'all')
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);

        if ($type == 'in_evidenza') {
            $query = $this->highlightedAndHomepageNewsQuery($params);
        } else {
            $query = $this->homepageNewsQuery($params);
        }

        $this->applySearchFilters($query);
        $this->applyExtraFilterSearch($query);

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                if (strpos($command, 'news_categorie_ids') !== false) {
                    $this->cmsFilterCategories($command, $query);
                } elseif (strpos($command, 'scope_community_id')) {
                    $communityId = $this->extractCommunityIdFromCommand($command);
                    $query = $this->cmsFilterScopeCommunity($query, $communityId);
                } else {
                    $query->andWhere(eval("return " . $command . ";"));
                }
            }
        }

        if(isset($params['orderByTitle']) && $params['orderByTitle']) {
            $query->orderBy('titolo ASC');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'id',
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }

        return $dataProvider;
    }

    /**
     * @param $command
     * @return string|null
     */
    public function extractCommunityIdFromCommand($command)
    {
        $community_id = null;
        $explode = explode('=>', $command);
        if (count($explode) == 2) {
            $community_id = trim($explode[1]);
        }
        return $community_id;
    }

    /**
     * @param $query
     * @param $community_id
     * @return mixed|\yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function cmsFilterScopeCommunity($query, $community_id)
    {
        if ($community_id) {
            $cwhConfig = CwhConfig::find()->andWhere(['tablename' => 'community'])->one();
            if ($cwhConfig) {
                $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery(
                    News::className(),
                    [
                        'queryBase' => $query,
                        'bypassScope' => false
                    ]
                );
                $query = $cwhActiveQuery->getQueryCwhAll($cwhConfig->id, $community_id, false);
            }
        }
        return $query;
    }

    /**
     * 
     */
    public function cmsSearchOwnInterestEvidenza($params, $limit = null)
    {
        if (\Yii::$app->user->isGuest) {
            $dataProvider = $this->cmsSearch($params, $limit, 'in_evidenza');
        } else {
            $dataProvider = $this->searchOwnInterest($params, $limit);
        }

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $dataProvider->query->limit(null);
        } else {
            $dataProvider->query->limit($limit);
        }
        return $dataProvider;
    }

    public function cmsSearchOwnInterest($params, $limit = null)
    {
        if (\Yii::$app->user->isGuest) {
            $dataProvider = $this->cmsSearch($params, $limit);
        } else {
            $dataProvider = $this->searchOwnInterest($params, $limit);
        }

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $dataProvider->query->limit(null);
        } else {
            $dataProvider->query->limit($limit);
        }
        return $dataProvider;
    }

    /**
     * This search is to retrieve the same news of the old WidgetGraphicsUltimeNews
     * @param array $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchUltimeNews($params, $limit = null)
    {
        if (\Yii::$app->user->isGuest) {
            $dataProvider = $this->cmsSearch($params, $limit);
        } else {
            $dataProvider = $this->ultimeNews($params, $limit);
        }

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $dataProvider->query->limit(null);
        } else {
            $dataProvider->query->limit($limit);
        }

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $dataProvider->query->andWhere(eval("return " . $command . ";"));
            }
        }

        return $dataProvider;
    }

    /**
     * Method Search useful to retrieve news to show in frontend (with cms)
     *
     * sort -> defaultOrder -> date_news = SORT_DESC
     *
     * @param array $params
     * @param [type] $limit
     * @return void
     */
    public function cmsSearchByDateNews($params, $limit = null)
    {

        $params = array_merge($params, Yii::$app->request->get());

        $this->load($params);
        $query = $this->homepageNewsQuery($params);
        $this->applySearchFilters($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_news' => SORT_DESC,
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
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterAgrifood($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_AGRIFOOD_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterAerospazio($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_AEROSPAZIO_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterChimicaverde($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_CHIMICAVERDE_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterMobilita($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_MOBILITA_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterFabbricaIntelligente($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_FABBRICAINTELLGENTE_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterEnergia($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_ENERGIA_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterTecnologieSmartCommunities($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_SMARTCOMUNITIESTEC_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterScienzeVita($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_SCIENZEVITA_ID);

    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchTecnologieAmbientiVita($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_AMBIENTIVITATEC_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchStorieInnovazione($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_STORIEINNOVAZIONE_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchLabLombardia($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_LABLOMBARDIA_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function cmsSearchCampusParty($params, $limit = null)
    {
        $this->load($params);
        $query = $this->baseSearch($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
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
                $query->andWhere(eval("return " . $command . ";"));
            }
        }

        return $dataProvider;
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function cmsSearchStatiGenerali($params, $limit = null)
    {
        $this->load($params);
        $query = $this->baseSearch($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
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
                $query->andWhere(eval("return " . $command . ";"));
            }
        }

        return $dataProvider;
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchPremio($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_PREMIO_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchForoRegionale($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_LEGGEREGIONALE_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchLeggeRegionale($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_LEGGEREGIONALE_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchRedazione($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_REDAZIONE_ID);
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @param int|null $category
     * @return ActiveDataProvider
     */
    public function cmsSearchByCategory($params, $limit = null, $category = null)
    {
        $tableName = $this->tableName();

        $this->load($params);
        $query = $this->homepageNewsQuery($params);

        $this->applySearchFilters($query);

        $query
            ->limit($limit)
            ->andWhere([
                $tableName . '.news_categorie_id' => $category,
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        return $dataProvider;
    }

    /**
     * @return array
     */
    public function cmsViewFields()
    {
        $viewFields = [
            new CmsField("titolo", "TEXT", 'amosnews', $this->attributeLabels()["titolo"]),
            new CmsField("descrizione_breve", "TEXT", 'amosnews', $this->attributeLabels()['descrizione_breve']),
            new CmsField("newsImage", "IMAGE", 'amosnews', $this->attributeLabels()['newsImage']),
            new CmsField("data_pubblicazione", "DATE", 'amosnews', $this->attributeLabels()['data_pubblicazione']),
        ];

        return $viewFields;
    }

    /**
     * @return array
     */
    public function cmsSearchFields()
    {
        $searchFields = [
            new CmsField("titolo", "TEXT"),
            new CmsField("sottotitolo", "TEXT"),
            new CmsField("descrizione_breve", "TEXT"),
            new CmsField("data_pubblicazione", "DATE")
        ];

        return $searchFields;
    }

    /**
     * @param int $id
     * @return boolean
     */
    public function cmsIsVisible($id)
    {
        $retValue = false;

        if (isset($id)) {
            $md = $this->findOne($id);
            if (!is_null($md)) {
                $retValue = $md->primo_piano;
            }
        }

        return $retValue;
    }

    /**
     * // Check if can use the custom module order
     *
     * @inheritdoc
     */
    public function searchDefaultOrder($dataProvider)
    {

        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort($this->createOrderClause());
        } else {
            // For widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                    'created_at' => SORT_DESC,
                ]
            ]);
        }

        return $dataProvider;
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
    public function searchAllQuery($params)
    {
        return $this->buildQuery($params, 'all');
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
    public function searchCreatedByMeQuery($params)
    {
        return $this->buildQuery($params, 'created-by');
    }


    /**
     * @param $command
     * @param $query ActiveQuery
     */
    public function cmsFilterCategories($command, $query)
    {
        $explode = explode('=>', $command);
        if (count($explode) == 2) {
            $val = trim($explode[1]);
            $val = str_replace('[', '', $val);
            $val = str_replace(']', '', $val);
            $categoryIds = explode(',', $val);
            $query->leftJoin('news_categorie_mm', 'news_categorie_mm.news_id = news.id')
                ->andWhere(['OR',
                    ['news_categorie_mm.news_categorie_id' => $categoryIds],
                    ['news.news_categorie_id' => $categoryIds]
                ]);
        }
    }

    /**
     * @param $query
     * @return void
     */
    public function applyExtraFilterSearch($query)
    {
        if (!empty($this->keyword)) {
            $query->andWhere(['OR',
                ['LIKE', 'titolo', $this->keyword],
                ['LIKE', 'descrizione', $this->keyword],
                ['LIKE', 'sottotitolo', $this->keyword],
                ['LIKE', 'descrizione_breve', $this->keyword],
            ]);
        }
    }

    /**
     * @param $params
     * @param $queryType
     * @param $limit
     * @param $onlyDrafts
     * @param $pageSize
     * 
     * return ActiveQuery
     */
    public function search($params, $queryType = null, $limit = null, $onlyDrafts = false, $pageSize = null)
    {
        $activeRecord = parent::search($params, $queryType, $limit, $onlyDrafts, $pageSize);
        $baseName = \open20\amos\core\utilities\ClassUtility::getClassBasename($this);
        if (isset($params[$baseName]['status'])) {
            $activeRecord->query->andFilterWhere([
                static::tableName()
                    . '.status' => $params[$baseName]['status']
                ]);
        }
        return $activeRecord;
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    public function searchSelectPeriodFilter($query)
    {
        $period = $this->period;
        $from = self::getMapSearchFromTime($period);
        $to = strtotime('now');

        if ($this->newsModule->dateFormatNews == DateControl::FORMAT_DATE) {
            $dateFrom = date('Y-m-d', $from);
            $dateTo = date('Y-m-d', $to);
        } else if ($this->newsModule->dateFormatNews == DateControl::FORMAT_DATETIME) {
            $dateFrom = date('Y-m-d H:i:s', $from);
            $dateTo = date('Y-m-d H:i:s', $to);
        }

        if (!empty($dateFrom)) {
            $query->andWhere(['>=', 'data_pubblicazione', $dateFrom]);
        }
        if (!empty($dateTo)) {
            $query->andWhere(['<=', 'data_pubblicazione', $dateTo]);
        }

        return $query;
    }

    /**
     * Get data_pubblicazione_from based on period
     * @param $period
     * @return int
     */
    public static function getMapSearchFromTime($period)
    {
        switch ($period) {
            case 'week':
                $from = strtotime('-1 week');
                break;
            case 'month':
                $from = strtotime('-1 month');
                break;
            case '3months':
                $from = strtotime('-3 months');
                break;
            case 'year':
                $from = strtotime('-1 year');
                break;
        }
        return $from;
    }

}
