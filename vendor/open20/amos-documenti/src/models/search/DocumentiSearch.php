<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models\search
 * @category   CategoryName
 */

namespace open20\amos\documenti\models\search;

use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\core\record\SearchResult;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCartellePath;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\cwh\query\CwhActiveQuery;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class DocumentiSearch
 * DocumentiSearch represents the model behind the search form about `open20\amos\documenti\models\Documenti`.
 * @package open20\amos\documenti\models\search
 */
class DocumentiSearch extends Documenti implements SearchModelInterface, ContentModelSearchInterface, CmsModelInterface
{
    private $container;
    public $parentId;
    public $dataPubblicazioneAl;
    public $ricerca_sottocartelle;

    public $data_pubblicazione_from;
    public $data_pubblicazione_to;
    public $extensions;
    public $genericText;
    public $solo_in_evidenza;

    /**
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
        $this->modelClassName = $this->documentsModule->model('Documenti');
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->data_pubblicazione = null;
        $this->data_rimozione = null;
        $this->documenti_categorie_id = null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'ricerca_sottocartelle' => AmosDocumenti::t('amosdocumenti', 'Ricerca nelle sottocartelle'),
            'data_pubblicazione_from' => AmosDocumenti::t('amosdocumenti', 'Data da'),
            'data_pubblicazione_to' => AmosDocumenti::t('amosdocumenti', 'Data a'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $integer = ['id', 'primo_piano', 'hits', 'abilita_pubblicazione', 'updated_by', 'deleted_by', 'parent_id', 'ricerca_sottocartelle','year'];
        if (!isset(\Yii::$app->params['hideListsContentCreatorName']) || (\Yii::$app->params['hideListsContentCreatorName']
                === false)) {
            $integer[] = 'created_by';
        }
        return [
            [$integer, 'integer'],
            ['genericText', 'string'],
            [['extensions', 'parentId', 'solo_in_evidenza', 'titolo', 'sottotitolo', 'descrizione_breve', 'descrizione', 'metakey', 'metadesc', 'data_pubblicazione', 'dataPubblicazioneAl',
                'data_rimozione', 'documenti_categorie_id', 'created_at', 'updated_at', 'deleted_at', 'data_pubblicazione_from', 'data_pubblicazione_to', 'ricerca_sottocartelle'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function searchFieldsMatch()
    {
        $fields = [
            'id',
            'primo_piano',
            'hits',
            'abilita_pubblicazione',
            'created_at',
            'updated_at',
            'deleted_at',
            'updated_by',
            'deleted_by',
            'documenti_categorie_id',
        ];
        if (!isset(\Yii::$app->params['hideListsContentCreatorName']) || (\Yii::$app->params['hideListsContentCreatorName']
                === false)) {
            $fields[] = 'created_by';
        }
        return $fields;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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


    public function getFoldersList($communityId, $id)
    {
		
		$documentiClassName = $this->documentsModule->model('Documenti');
        
		/** @var Documenti $documentiModel */
            $documentiModel = $this->documentsModule->createModel('Documenti');
            /** @var ActiveQuery $query */
            $query          = $documentiModel::find()->distinct();
			
		$cwhActiveQuery = new CwhActiveQuery($documentiClassName, ['queryBase' => $query]);
		$queryAll = $cwhActiveQuery->getQueryCwhAll(null, null, true);
		$queryAll->andWhere(['is_folder' => 1])
		->andWhere(['not', ['documenti.id' => $id]])
		->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO]);
		
		$data = DocumentiSearch::find()->andWhere(['is_folder' => 1])->andWhere([
                'in',
                'id',
                $queryAll->select('documenti.id')->asArray()->column()
            
		 ])
            ->andWhere(['not', ['id' => $id]])
            ->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
            ->asArray()->all();
        return $data;
    }

    /**
     * Use to add Join condition/add other filtering condition
     *
     * @param ActiveQuery $query
     */
    public function getSearchQuery($query)
    {

        /** @var AmosDocumenti $documentModule */
        $documentModule = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        if (isset($documentModule)) {
            if ($documentModule->hidePubblicationDate == false) {
                $query->andFilterWhere([
                    '>=', 'data_pubblicazione', $this->data_pubblicazione,
                ]);

                $query->andFilterWhere([
                    '<=', 'data_pubblicazione', $this->dataPubblicazioneAl,
                ]);

            }
        }

        if (!empty($this->data_pubblicazione_from)) {
            $query->andFilterWhere(['>=', new Expression("DATE(data_pubblicazione)"), new Expression("DATE('{$this->data_pubblicazione_from}')")]);
        }

        if (!empty($this->data_pubblicazione_to)) {
            $query->andFilterWhere(['<=', new Expression("DATE(data_pubblicazione)"), new Expression("DATE('{$this->data_pubblicazione_to}')")]);
        }

        if (!empty($this->extensions)) {
            $query->leftJoin('attach_file', new Expression('attach_file.item_id = documenti.id AND model = "open20\\\\amos\\\\documenti\\\\models\\\\Documenti"'));
            $query->andWhere(['attach_file.type' => $this->extensions]);
//            var_dump($query->createCommand()->rawSql);
//            var_dump($query->createCommand()->rawSql);
//            var_dump($query->createCommand()->rawSql);
//            print_r($query->createCommand()->rawSql);
        }

    }

    public function additionalQueryTypes($query, $queryType, $cwhActiveQuery) {
        if ($queryType == 'all-in-all-statuses') {
            $classname = $this->modelClassName;
            if (\Yii::$app->getModule('cwh') && in_array($classname, \Yii::$app->getModule('cwh')->modelsEnabled) && !Yii::$app->user->isGuest) {
                /** @var  $queryAll ActiveQuery */
                $queryAll = $cwhActiveQuery->getQueryCwhAll(null, null, true);
                $queryAll->select('documenti.id');

                $cwhActiveQuery2 = clone $cwhActiveQuery;
                $queryOwn = $cwhActiveQuery2->getQueryCwhOwn();
                $queryOwn->select('documenti.id');

                $cwhActiveQuery3 = clone $cwhActiveQuery;
                $queryToValidate = $cwhActiveQuery3->getQueryCwhToValidate();
                $queryToValidate->select('documenti.id');
                ;

                $subquery = $queryAll->union($queryOwn)->union($queryToValidate)->select('documenti.id');

                if ($this->documentsModule->documentMainFileRequired) {
                    $query = Documenti::find()->andWhere(['documenti.id' => $subquery]);
                }
            } else {
                $query->andWhere(['OR',
                    [static::tableName() . '.status' => $this->getValidatedStatus()],
                    [static::tableName() . '.status' => $this->getToValidateStatus()],
                    [static::tableName() . '.created_by' => Yii::$app->user->id],
                ]);
            }
        }

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function search($params, $queryType = null, $limit = null, $onlyDratfs = false, $showAll = false)
    {
        $getParentId = \Yii::$app->request->get('parentId');
        if ($getParentId) {
            $params['DocumentiSearch']['parentId'] = $getParentId;
        }
        $query = $this
            ->buildQuery($params, $queryType)
            ->limit($limit);

        /** Switch off notifications - method of NotifyRecord */
        $this->switchOffNotifications($query);

        $dp_params = ['query' => $query,];
        if ($limit) {
            $dp_params ['pagination'] = false;
        }

        if (\Yii::$app->user->isGuest) {
            $query->andWhere(['primo_piano' => 1]);
        }
        
        $documentsModule = AmosDocumenti::instance();
        if($documentsModule->enableAnnualArchiving && !empty($params['DocumentiSearch']['year'])){
           $query->andWhere(['enable_annual_archiving' => 1]);
           $query->andWhere([
               'or',
                [
                    'and',
                    ['year' => $params['DocumentiSearch']['year']],
                    ['<','year', date('Y')],
                    ['date_start_archiving' => null],
                    ['<>','stop_archiving', 1],
                    
                ],
                [
                    'and',
                    ['year' => $params['DocumentiSearch']['year']],
                    ['<=', 'date_start_archiving', date('Y-m-d')],
                    ['<>','stop_archiving', 1],
                    
                   
                ],
               
            ]);
           
        }

        //set the data provider
        $dataProvider = new ActiveDataProvider($dp_params);
        // Order with search order attribute
        $dataProvider = $this->searchDefaultOrder($dataProvider);
        // Default order
        if (empty($params['DocumentiSearch']['orderAttribute'])) {
            $dataProvider->setSort($this->getDefaultOrder());
        }

        //if you don't use the seach form, the recursive search is not active
        $this->load($params);
        //se non vengo da un widget grafico e l'action nn è da validare e  creato da
        if ((!in_array($queryType, ['to-validate', 'created-by'])) && !(!empty($params['fromWidgetGraphic']) && $params['fromWidgetGraphic'] == true)) {
            if (!$showAll) {
                if (!$params['DocumentiSearch']['ricerca_sottocartelle']) {
                    $query->andWhere([self::tableName() . '.parent_id' => $this->parentId]);
                } else {
                    $query->innerJoin(
                        DocumentiCartellePath::tableName(),
                        self::tableName() . '.id = ' . DocumentiCartellePath::tableName() . '.id_doc_folder')
                        ->andWhere([DocumentiCartellePath::tableName() . '.id_folder' => $this->parentId]);
                }
            }
        }
        


        // recursive search
        if (!empty($this->parentId)) {
            $query->andWhere([self::tableName() . '.parent_id' => $this->parentId]);           
        }

        //if parentid empty the search is without (parent_id IS NULL)
        if (isset($params[$this->formName()]['tagValues'])) {

            $tagValues = $params[$this->formName()]['tagValues'];
            $this->setTagValues($tagValues);
            if (is_array($tagValues) && !empty($tagValues)) {
                $orQueries = null;
                $i = 0;
                foreach ($tagValues as $rootId => $tagId) {
                    if (!empty($tagId)) {
                        if ($i == 0) {
                            $query->innerJoin('entitys_tags_mm entities_tag',
                                "entities_tag.classname = '" . addslashes($this->modelClassName) . "' AND entities_tag.record_id=" . static::tableName() . ".id");
                            $orQueries[] = 'or';
                        }
                        $tags = explode(',', $tagId);
                        $tags = array_unique($tags);
                        $orQueries[] = ['and', ["entities_tag.tag_id" => $tags], ['entities_tag.root_id' => $rootId], ['entities_tag.deleted_at' => null]];
                        $i++;
                    }
                }
                if (!empty($orQueries)) {
                    $query->andWhere($orQueries);
                }
            }
        }

        $this->applySearchFilters($query);
        $this->getSearchQuery($query);
//pr($query->createCommand()->rawSql);die;
//        VarDumper::dump($query->createCommand()->rawSql,3,true);
          
        return $dataProvider;
    }


    /**
     * @param AmosModule $moduleCwh
     * @param string $classname
     * @return bool
     */
    private function isSetCwh($moduleCwh, $classname)
    {
        if (isset($moduleCwh) && in_array($classname, $moduleCwh->modelsEnabled)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Documents base search: all documents matching search parameters and not deleted.
     *
     * @param array $params Search parameters
     * @return \yii\db\ActiveQuery
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);

        /** @var \yii\db\ActiveQuery $baseQuery */
        $documentModel = $this->documentsModule->model('Documenti');
        $baseQuery = $documentModel::find()->distinct();

        if ($this->documentsModule->enableDocumentVersioning) {
            $baseQuery->andWhere(['version_parent_id' => null]);
        }

        return $baseQuery;
    }

    /**
     * Search the Documents created by the logged user
     *
     * @param array $params Array di parametri per la ricerca
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchOwnDocuments($params, $limit = null)
    {
        return $this->search($params, 'created-by', $limit);
    }

    /**
     * Search documents to validate based on cwh rules if cwh is active, all documents in 'to validate status' otherwise
     *
     * @param array $params Array di parametri per la ricerca
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchToValidateDocuments($params, $limit = null)
    {
        return $this->search($params, 'to-validate', $limit);
    }

    /**
     * Search last documents in validated status, generally the limit is set to 3 (by last documents graphic widget)
     *
     * @param array $params Array of search parameters
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function lastDocuments($params, $limit = null)
    {
        $params = array_merge($params, Yii::$app->request->getQueryParams());
        $params['fromWidgetGraphic'] = true;
        $dataProvider = $this->searchAll($params, $limit);

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $dataProvider->query->andWhere(eval("return " . $command . ";"));
            }
        }
        return $dataProvider;
    }

    /**
     * Search method for actionAllDocuments
     * @param $params
     * @param $limit
     * @return ActiveDataProvider|\yii\data\BaseDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchAllDocuments($params, $limit = null)
    {
        if ($this->documentsModule->showAllStatusesAllDocument) {
            $dataProvider = $this->searchAllInAllStatuses($params, $limit);
        } else {
            $dataProvider = $this->searchAll($params, $limit);
        }
        if (\Yii::$app->user->isGuest) {
            $dataProvider->query->andWhere(['primo_piano' => 1]);
        }

        return $dataProvider;
    }

    public function searchAllInAllStatuses($params, $limit = null)
    {
        return $this->search($params, 'all-in-all-statuses', $limit);
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchAdminAll($params, $limit = null)
    {
        return $this->search($params, 'admin-all', $limit);
    }

    /**
     * Search method useful to retrieve all validated documenti (based on publication rule and visibility).
     *
     * @param array $params Array of get parameters for search
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function searchOwnInterest($params, $limit = null, $showAll = false)
    {

        return $this->search($params, 'own-interest', $limit, false, $showAll);
    }

    /**
     * Search method useful to retrieve documents in validated status with both flags primo_piano and in_evidenza true
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHighlightedAndHomepageDocumenti($params)
    {
        $query = $this->highlightedAndHomepageDocumentiQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Search method useful to retrieve documents in validated status with flag primo_piano = true
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHomepageDocuments($params)
    {
        $query = $this->homepageDocumentsQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                ],
            ],
        ]);

//        if (!($this->load($params) && $this->validate())) {
//            return $dataProvider;
//        }

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    public function highlightedAndHomepageDocumentiQuery($params)
    {
        return $this
            ->baseSearch($params)
            ->andWhere([
                self::tableName() . '.deleted_at' => null,
                self::tableName() . '.status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
                self::tableName() . '.in_evidenza' => 1,
                self::tableName() . '.primo_piano' => 1,
            ]);
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    public function homepageDocumentsQuery($params)
    {
        return $this
            ->baseSearch($params)
            ->distinct()->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '" . str_replace('\\', '\\\\', Documenti::className()) . "' and " . EntitysTagsMm::tableName() . ".record_id = " . Documenti::tableName() . ".id and " . EntitysTagsMm::tableName() . ".deleted_at is NULL")
            ->andWhere([
                self::tableName() . '.deleted_at' => null,
                self::tableName() . '.status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
                self::tableName() . '.primo_piano' => 1,
            ]);
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchVersions($params)
    {
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        $query = $documentiModel::find()
            ->andFilterWhere([
                'version_parent_id' => $params['parent_id'],
            ])
            ->andFilterWhere(['!=', 'id', $params['parent_id']])
            ->orderBy('version DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * @param object $model The model to convert into SearchResult
     * @return SearchResult
     */
    public function convertToSearchResult($model)
    {
        $searchResult = new SearchResult();

        $searchResult->url = $model->getFullViewUrl();
        $searchResult->box_type = "file";
        $searchResult->id = $model->id;
        $searchResult->titolo = $model->titolo;
        $searchResult->data_pubblicazione = $model->data_pubblicazione;
        $searchResult->documento = $model->getDocumentMainFile();
        $searchResult->abstract = $model->descrizione_breve;

        return $searchResult;
    }

    /**
     * @inheritdoc
     */
    public function searchDefaultOrder($dataProvider)
    {
        // Check if can use the custom module order
        if ($this->canUseModuleOrder()) {
//            $dataProvider->setSort($this->createOrderClause());
            $dataProvider->setSort([
                'attributes' => [
                    'documenti.titolo',
                    'documenti.updated_at',
//                    'documenti.data_pubblicazione',
                ],
                'defaultOrder' => [
                    $this->orderAttribute => (int) $this->orderType
                ]
            ]);
        } else {
            // For widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => ['data_pubblicazione' => SORT_DESC]
            ]);
        }

        return $dataProvider;
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
     * @inheritdoc
     */
    public function cmsSearch($params, $limit)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $query = $this->homepageDocumentsQuery($params);
        $this->applySearchFilters($query);
        $this->applyExtraFilterSearch($query);

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                if (strpos($command, 'scope_community_id')) {
                    $communityId = $this->extractCommunityIdFromCommand($command);
                    $query = $this->cmsFilterScopeCommunity($query, $communityId);
                } else {
                    $query->andWhere(eval("return " . $command . ";"));
                }
            }
        }

        if(isset($params['hideFolder']) && $params['hideFolder']) {
            $query->andWhere(['is_folder' => 0]);
        }

        if(isset($params['orderByTitle']) && $params['orderByTitle']) {
            $query->orderBy('titolo ASC');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                ],
            ],
        ]);

        $query->andWhere(['primo_piano' => 1]);

        $query->andFilterWhere(['parent_id' => $this->parent_id]);
        $query->andFilterWhere(['in_evidenza' => $this->solo_in_evidenza]);

        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }

        return $dataProvider;
    }

    /**
     * @param $query
     * @return void
     */
    public function applyExtraFilterSearch($query)
    {
        if (!empty($this->genericText)) {
            $query->andWhere(['OR',
                ['LIKE', 'titolo', $this->genericText],
                ['LIKE', 'descrizione', $this->genericText],
                ['LIKE', 'sottotitolo', $this->genericText],
                ['LIKE', 'descrizione_breve', $this->genericText],
            ]);
        }
        if (!empty($this->extensions)) {
            $query->leftJoin('attach_file', new Expression('attach_file.item_id = documenti.id AND model = "open20\\\\amos\\\\documenti\\\\models\\\\Documenti"'));
            $query->andWhere(['attach_file.type' => $this->extensions]);
        }
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
                    Documenti::className(),
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
     * Search method useful to retrieve all validated documenti (based on
     * publication rule and visibility). tthis method is specific for pages
     * generated by the CMS
     *
     * @param array $params Array of get parameters for search
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchOwnInterest($params, $limit = null)
    {
        if (\Yii::$app->user->isGuest) {
            $dataProvider = $this->cmsSearch($params, $limit);
        } else {
            $params = array_merge($params, Yii::$app->request->get());
            $this->load($params);
            $dataProvider = $this->searchOwnInterest($params, $limit);

            if (!empty($this->genericText)) {
                $tabDocs = self::tableName();
                $dataProvider->query->andWhere(['OR',
                    ['LIKE', $tabDocs . '.titolo', $this->genericText],
                    ['LIKE', $tabDocs . '.descrizione', $this->genericText],
                    ['LIKE', $tabDocs . '.sottotitolo', $this->genericText],
                    ['LIKE', $tabDocs . '.descrizione_breve', $this->genericText],
                ]);
            }

            if (!empty($params["conditionSearch"])) {
                $commands = explode(";", $params["conditionSearch"]);
                foreach ($commands as $command) {
                    if (strpos($command, 'scope_community_id')) {
                        $communityId =
                            $this->extractCommunityIdFromCommand($command);
                        $dataProvider->query =
                            $this->cmsFilterScopeCommunity($dataProvider->query,
                                $communityId);
                    } else {
                        $dataProvider->query->andWhere(eval("return " . $command
                            . ";"));
                    }
                }
            }

            if (!empty($params["withPagination"])) {
                $dataProvider->setPagination(['pageSize' => $limit]);
                $dataProvider->query->limit(null);
            } else {
                $dataProvider->query->limit($limit);
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
        array_push($searchFields, new CmsField("descrizione_breve", "TEXT"));

        return $searchFields;
    }

    /**
     * @inheritdoc
     */
    public function cmsViewFields()
    {
        return [
            new CmsField('titolo', 'TEXT', 'amosdocumenti', $this->attributeLabels()['titolo']),
            new CmsField('descrizione', 'TEXT', 'amosdocumenti', $this->attributeLabels()['descrizione']),
            new CmsField('descrizione_breve', 'TEXT', 'amosdocumenti', $this->attributeLabels()['descrizione_breve']),
            new CmsField('documentMainFile', 'IMAGE', 'amosdocumenti', $this->attributeLabels()['documentMainFile'])
        ];
    }

    /**
     * @return array[]
     */
    public function getDefaultOrder()
    {
        $actionId = Yii::$app->controller->action->id;
        /** @var AmosDocumenti $moduleDocumenti */
        $moduleDocumenti = AmosDocumenti::instance();

        $defaultOrder = [
            'defaultOrder' => [
                'data_pubblicazione' => SORT_DESC,
            ]
        ];

        // Overwrites default order if folders are enabled
        if ($moduleDocumenti->enableFolders) {
            $defaultOrder = [
                'defaultOrder' => [
                    'is_folder' => SORT_DESC,
                    'data_pubblicazione' => SORT_DESC,
                ]
            ];
        }

        // Overwrites default order based on current action
        if (in_array($actionId, ['all-documents', 'own-interest-documents', 'admin-all-documents'])) {
            if ($moduleDocumenti->enableFolders) {
                $defaultOrder = [
                    'defaultOrder' => [
                        'is_folder' => SORT_DESC,
                        'titolo' => SORT_ASC,
                    ]
                ];
            } else {
                $defaultOrder = [
                    'defaultOrder' => [
                        'titolo' => SORT_ASC,
                    ]
                ];
            }
        }
        if (in_array($actionId, ['own-documents', 'to-validate-documents'])) {
            $defaultOrder = [
                'defaultOrder' => [
                    'updated_at' => $actionId == 'to-validate-documents' ? SORT_ASC : SORT_DESC,
                ]
            ];
        }

        return $defaultOrder;
    }
}