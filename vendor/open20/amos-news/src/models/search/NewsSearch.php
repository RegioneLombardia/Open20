<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\models\search
 * @category   CategoryName
 */

namespace open20\amos\news\models\search;

use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\news\models\News;
use open20\amos\tag\models\EntitysTagsMm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ExpressionInterface;
use yii\di\Container;

/**
 * NewsSearch represents the model behind the search form about `open20\amos\news\models\News`.
 */
class NewsSearch extends News implements SearchModelInterface, ContentModelSearchInterface, CmsModelInterface
{

    /** @var  Container $container - used by ContentModel do not remove */
    private
        $container;

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

        $this->modelClassName = News::className();
    }

    /**
     */
    public function rules()
    {
        return [
            [['id', 'primo_piano', 'hits', 'abilita_pubblicazione', 'news_categorie_id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['titolo', 'sottotitolo', 'descrizione_breve', 'descrizione', 'metakey', 'metadesc', 'data_pubblicazione', 'data_rimozione', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
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
        return $this->search($params, "all", $limit);
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchAdminAll($params, $limit = null)
    {
        return $this->search($params, "admin-all", $limit);
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
        return $this->search($params, "own-interest", $limit);
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

        // TBD FRANZ - vero o non vero ritorna sempre e comunque
        // lo stesso $dataProvider a che serve allora?
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
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
        $now = date('Y-m-d');
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->andWhere([
                $tableName . '.status' => News::NEWS_WORKFLOW_STATUS_VALIDATO,
                $tableName . '.in_evidenza' => 1,
                $tableName . '.primo_piano' => 1
            ])
            ->andWhere(['<=', 'data_pubblicazione', $now])
            ->andWhere(['or',
                    ['>=', 'data_rimozione', $now],
                    ['data_rimozione' => null]]
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
        $now = date('Y-m-d');
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->distinct()->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '".  str_replace('\\','\\\\',News::className()) . "' and ".EntitysTagsMm::tableName(). ".record_id = ". News::tableName() . ".id  and " . EntitysTagsMm::tableName(). ".deleted_at is NULL")
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
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearch($params, $limit = null)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $query = $this->homepageNewsQuery($params);
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
//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_AGRIFOOD_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterAerospazio($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_AEROSPAZIO_ID);
//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_AEROSPAZIO_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterChimicaverde($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_CHIMICAVERDE_ID);
//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_CHIMICAVERDE_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterMobilita($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_MOBILITA_ID);

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_MOBILITA_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterFabbricaIntelligente($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_FABBRICAINTELLGENTE_ID);

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_FABBRICAINTELLGENTE_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterEnergia($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_ENERGIA_ID);

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_ENERGIA_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterTecnologieSmartCommunities($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_SMARTCOMUNITIESTEC_ID);

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_SMARTCOMUNITIESTEC_ID,
//        ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchClusterScienzeVita($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_SCIENZEVITA_ID);

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_SCIENZEVITA_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchTecnologieAmbientiVita($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_AMBIENTIVITATEC_ID);

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_AMBIENTIVITATEC_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchStorieInnovazione($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_STORIEINNOVAZIONE_ID);
//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_STORIEINNOVAZIONE_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchLabLombardia($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_LABLOMBARDIA_ID);
//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_LABLOMBARDIA_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
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

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_PREMIO_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchForoRegionale($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_LEGGEREGIONALE_ID);

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_FOROREGIONALE_ID,
//      ]);
//      
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchLeggeRegionale($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_LEGGEREGIONALE_ID);

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_LEGGEREGIONALE_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
    }

    /**
     * @param array $params
     * @param int|ExpressionInterface|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchRedazione($params, $limit = null)
    {
        return $this->cmsSearchByCategory($params, $limit, News::NEWS_CLUSTERCAT_REDAZIONE_ID);

//    $tableName = $this->tableName();
//
//    $this->load($params);
//    $query = $this->homepageNewsQuery($params);
//    $this->applySearchFilters($query);
//    
//    $query
//      ->limit($limit)
//      ->andWhere([
//        $tableName . '.news_categorie_id' => News::NEWS_CLUSTERCAT_REDAZIONE_ID,
//      ]);
//    
//    $dataProvider = new ActiveDataProvider([
//      'query' => $query,
//      'sort' => [
//        'defaultOrder' => [
//          'data_pubblicazione' => SORT_DESC,
//        ],
//      ],
//    ]);
//    
//    return $dataProvider;
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
        $viewFields = [];

//    array_push($viewFields, new CmsField("titolo", "TEXT", 'amosnews', $this->attributeLabels()["titolo"]));
//    array_push($viewFields, new CmsField("descrizione_breve", "TEXT", 'amosnews', $this->attributeLabels()['descrizione_breve']));
//    array_push($viewFields, new CmsField("newsImage", "IMAGE", 'amosnews', $this->attributeLabels()['newsImage']));
//    array_push($viewFields, new CmsField("data_pubblicazione", "DATE", 'amosnews', $this->attributeLabels()['data_pubblicazione']));

        $viewFields[] = new CmsField("titolo", "TEXT", 'amosnews', $this->attributeLabels()["titolo"]);
        $viewFields[] = new CmsField("descrizione_breve", "TEXT", 'amosnews', $this->attributeLabels()['descrizione_breve']);
        $viewFields[] = new CmsField("newsImage", "IMAGE", 'amosnews', $this->attributeLabels()['newsImage']);
        $viewFields[] = new CmsField("data_pubblicazione", "DATE", 'amosnews', $this->attributeLabels()['data_pubblicazione']);

        return $viewFields;
    }

    /**
     * @return array
     */
    public function cmsSearchFields()
    {
        $searchFields = [];

//    array_push($searchFields, new CmsField("titolo", "TEXT"));
//    array_push($searchFields, new CmsField("sottotitolo", "TEXT"));
//    array_push($searchFields, new CmsField("descrizione_breve", "TEXT"));
//    array_push($searchFields, new CmsField("data_pubblicazione", "DATE"));

        $searchFields[] = new CmsField("titolo", "TEXT");
        $searchFields[] = new CmsField("sottotitolo", "TEXT");
        $searchFields[] = new CmsField("descrizione_breve", "TEXT");
        $searchFields[] = new CmsField("data_pubblicazione", "DATE");

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
}
