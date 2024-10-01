<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch\models;

use open20\elasticsearch\base\ElasticConst;
use open20\elasticsearch\Module;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Json;

class ElasticQuery extends BaseObject {

    private $module;
    public $query = null;
    private $index = '_all';
    private $type = null;
    private $size = null;
    private $scroll_id = null;
    private $from = null;
    private $should = [];
    private $must = [];
    private $filter = [];
    private $minimum_should_match = 1;
    private $sort = [];
    private $functionScore = null;
    public $searchText = '';

    public $currentLang;

    public function __construct($lang = null)
    {
        $this->module = Module::instance();
        if ($this->module->enableI18N) {
            $this->currentLang = $lang;
        }
    }

    public function init() {
        $this->matchAllQuery();
    }

    public function getModule() {
        return $this->module;
    }

    public function getMinimum_should_match() {
        return $this->minimum_should_match;
    }

    public function setMinimum_should_match($minimum_should_match) {
        $this->minimum_should_match = $minimum_should_match;
    }

    public function getIndex($index = '*')
    {
        $index_name = $this->getModule()->indexPrefixName . $index;
        if ($this->module->enableI18N) {
            $index_name .= '-'
                . $this->currentLang;
        }

        return $index_name;
    }
    /**
     *
     * @param string $query
     * @param string $index
     * @param string $type
     */
    public function query($query = null, $index = '*', $type = null, $sort = []) {

        $this->query = $query;
        $this->index = $this->getIndex($index); // $this->getModule()->indexPrefixName . $index;
        $this->type = $type;
        $this->sort = $sort;
        return $this;
    }

    /**
     *
     * @param type $index
     * @param type $type
     * @return $this
     */
    public function boolQuery($index = '*', $type = null) {
        $this->query = ["query" => [
                "bool" => []
        ]];
        $this->index = $this->getIndex($index); // $this->getModule()->indexPrefixName . $index;
        if (!is_null($type)) {
            $this->type = $type;
        }
        return $this;
    }

    /**
     *
     * @param type $index
     * @param type $type
     * @return $this
     */
    public function matchAllQuery($index = '*', $type = null) {
        $this->query = ["query" => [
            "match_all" => (object) null
        ]];
        $this->index = $this->getIndex($index); // $this->getModule()->indexPrefixName . $index;
        if (!is_null($type)) {
            $this->type = $type;
        }
        return $this;
    }

    /**
     *
     * @param string $field
     * @param string $condition
     * @param int $end
     * @param string $index
     * @param string $type
     * @return $this
     */
    public function spanFirstQuery($field, $condition, $end = 1, $index = '*', $type = null) {
        $cmd = ["query" => [
                "span_first" => [
                    "match" => [
                        "span_multi" => [
                            "match" => [
                                "prefix" => [
                                    $field => [
                                        "value" => $condition
                                    ]
                                ]
                            ]
                        ]
                    ],
                    "end" => $end
                ]
        ]];
        $this->query = $cmd;
       $this->index = $this->getIndex($index); // $this->getModule()->indexPrefixName . $index;
         if (!is_null($type)) {
            $this->type = $type;
        }
        return $this;
    }

    /**
     *
     * @return type
     */
    private function buildParams() {
        $params = [
            'index' => $this->index,
            'body' => $this->buildQuery()
        ];

        if (!is_null($this->type)) {
            $params ['type'] = $this->type;
        }
        if (!is_null($this->from)) {
            $params ["from"] = $this->from;
        }
        if (!is_null($this->size) && $this->size) {
            $params ["size"] = $this->size;
        }
        return $params;
    }

    /**
     * @param string $queryTye
     * @param array $functions
     * @param string $score_mode
     */
    public function functionScoreQuery($queryTye = 'bool', $functions = [], $score_mode = 'sum') {
        $this->functionScore = [
            'queryType' => $queryTye,
            'functions' => $functions,
            'score_mode' => $score_mode
        ];
    }

    /**
     *   Esempio uso function_score
     *         $query = $this->query["query"]['bool'];
      $this->query = [];
      $this->query['query']['function_score'] = [
      'query' => ['bool' => $query],
      'functions' => [
      [
      'gauss' => ['start_publication' => ['scale' => '30d']],
      ]
      ],
      'score_mode' => 'sum'
      ];
     */
    public function addQueryToFunctionScore() {
        $queryType = $this->functionScore['queryType'];
        $functions = $this->functionScore['functions'];
        $score_mode = $this->functionScore['score_mode'];
        $query = $this->query["query"][$queryType];

        $this->query = [];
        $this->query['query']['function_score'] = [
            'query' => [$queryType => $query],
//            'functions' => [],
            'functions' => $functions,
            'boost_mode' => $score_mode
        ];
    }

    /**
     *
     * @return string
     */
    protected function buildQuery() {
        if (is_null($this->query)) {
            $this->matchAllQuery();
        }

        if (count($this->must)) {
            $this->query["query"]["bool"]["must"] = $this->must;
        }
        if (count($this->filter)) {
            $this->query["query"]["bool"]["filter"] = $this->filter;
        }
        if (count($this->should)) {
            $this->query["query"]["bool"]["should"] = $this->should;
            if ($this->minimum_should_match != 0) {
                $this->query["query"]["bool"]['minimum_should_match'] = $this->minimum_should_match;
            }
        } 

        if (count($this->sort)) {
            $this->query['sort'] = $this->sort;
        }

        if (!empty($this->functionScore)) {
            $this->addQueryToFunctionScore();
        }

        return Json::encode($this->query);
    }

    /**
     *
     * @param array $query_result
     * @return array
     */
    public function buildResult(array $query_result) {
        $models = [];

        $elastic_models = $query_result['hits']['hits'];
        foreach ($elastic_models as $emodel) {
            $transformer = $this->module->transformerManager->getTrasnformerManager(ElasticIndex::classFromIndexType($emodel['_type']));
            $models[] = $transformer->elasticToModel($emodel);
        }

        return $models;
    }

    /**
     *
     * @return type
     */
    public function count() {
        $params = $this->buildParams();

        if (isset($params['body'])) {
            $body = json_decode($params['body'], true);
            if (isset($body['sort'])) {
                unset($body['sort']);
            }
            $params['body'] = json_encode($body);
        }

        $results = $this->module->client->count($params);

        return $results['count'];
    }

    /**
     *
     * @return array
     */
    public function all($decode = true) {

        $results = $this->search($this->buildParams());
        $this->saveHistory($results);
        if ($this->size) {
            $this->scroll_id = $results['_scroll_id'];
        }
        return ($decode ? $this->buildResult($results) : $results);
    }

    /**
     * @param $result
     */
    public function saveHistory($result) {
        if (!empty($this->module)) {
            $total = 0;
            $configHistory = $this->module->history;
            if (!empty($result['hits']['total'])) {
                $total = $result['hits']['total'];
            }

            if (!empty($configHistory) && !empty($this->searchText) && $this->searchText != ' ') {
                $count = ElasticsearchHistory::find()
                                ->andWhere(['search_text' => $this->searchText])
                                ->andWhere(['created_at' => date('Y-m-d H:i:s')])->count();

                if ($count == 0) {
                    $history = new ElasticsearchHistory();
                    $history->search_text = $this->searchText;
                    $history->results = json_encode($result);
                    $history->tot_found = $total;
                    $history->user_id = Yii::$app->user->id;
                    $history->save(false);
                }
            }
        }
    }

    /**
     *
     * @return type
     */
    public function one($decode = true) {
        $params = $this->buildParams();
        $params['size'] = 1;
        unset($params['from']);
        $results = $this->search($params);
        return ($decode ? $this->buildResult($results) : $results);
    }

    /**
     *
     * @return type
     */
    public function search($params) {
        return $this->module->client->search($params);
    }

    /**
     *
     * @param int $limit
     * @return $this
     */
    public function limit($limit) {
        $this->size = $limit;
        return $this;
    }

    public function from($from) {
        $this->from = $from;
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $condition
     * @param type $operation
     * @return $this
     */
    public function addShould($field, $condition, $operation = 'match') {
        if (is_array($condition)) {
            foreach ($condition as $value) {
                $this->should[] = (new ItemCondition(['field' => $field, 'condition' => $value, 'operation' => $operation]))->toArray();
            }
        } else {
            $this->should[] = (new ItemCondition(['field' => $field, 'condition' => $condition, 'operation' => $operation]))->toArray();
        }
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $condition
     * @param type $as
     * @return $this
     */
    public function addAsBoolShould($field, $condition, $operation = "term", $as = "should") {
        $toAdd = [
            'bool' => [
                $as => []
            ]
        ];

        if (is_array($condition)) {
            foreach ($condition as $value) {
                $toAdd['bool'][$as][] = (new ItemCondition(['field' => $field, 'condition' => $value, 'operation' => $operation]))->toArray();
            }
        } else {
            $toAdd['bool'][$as][] = (new ItemCondition(['field' => $field, 'condition' => $condition, 'operation' => $operation]))->toArray();
        }
        $this->should[] = $toAdd;
        return $this;
    }

    /**
     *
     * @return $this
     */
    public function resetShoulds() {
        $this->should = [];
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $condition
     * @param type $operation
     * @return $this
     */
    public function addMust($field, $condition, $operation = 'match', $analyzer = "", $boostFields = [], $lenient = false) {
        if (is_array($condition)) {
            foreach ($condition as $value) {
                $item = new ItemCondition(['field' => $field, 'condition' => $value, 'operation' => $operation, 'lenient' => $lenient]);
                $item->setAnalyzer($analyzer);
                $item->setFields($boostFields);
                $this->must[] = $item->toArray();
            }
        } else {
            $item = new ItemCondition(['field' => $field, 'condition' => $condition, 'operation' => $operation, 'lenient' => $lenient]);
            $item->setAnalyzer($analyzer);
            $item->setFields($boostFields);
            $this->must[] = $item->toArray();
        }
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $condition
     * @param type $as
     * @return $this
     */
    public function addAsBoolMust($field, $condition, $operation = "term", $as = "should") {
        $toAdd = [
            'bool' => [
                $as => []
            ]
        ];

        if (is_array($condition)) {
            foreach ($condition as $value) {
                $toAdd['bool'][$as][] = (new ItemCondition(['field' => $field, 'condition' => $value, 'operation' => $operation]))->toArray();
            }
        } else {
            $toAdd['bool'][$as][] = (new ItemCondition(['field' => $field, 'condition' => $condition, 'operation' => $operation]))->toArray();
        }
        $this->must[] = $toAdd;
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $condition
     * @return $this
     */
    public function addMustLike($field, $condition) {
        $this->addMust($field, ElasticConst::WILDCARD_MORE . $condition . ElasticConst::WILDCARD_MORE, 'match');
        return $this;
    }

    /**
     *
     * @param string $condition
     * @return $this
     */
    public function addMustFullText($condition) {
        $this->resetMust();
        $this->must[] = (new ItemCondition(['field' => "query", 'condition' => $condition, 'operation' => "query_string"]))->toArray();
        return $this;
    }

    public function resetMust() {
        $this->must = [];
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $condition
     * @return $this
     */
    public function addFilter($field, $condition, $operation = "term") {
        if($operation == 'terms'){ 
            $this->filter[] = (new ItemCondition(['field' => $field, 'condition' => $condition, 'operation' => $operation]))->toArray();
        }  else if (is_array($condition)) {
            foreach ($condition as $value) {
                if ($value instanceof BaseItemCondition) {
                    $value->setField($field);
                    $this->filter[] = $value->toArray();
                } else {
                    $this->filter[] = (new ItemCondition(['field' => $field, 'condition' => $value, 'operation' => $operation]))->toArray();
                }
            }
        } else {
            if ($condition instanceof BaseItemCondition) {
                $condition->setField($field);
                $this->filter[] = $condition->toArray();
            } else {
                $this->filter[] = (new ItemCondition(['field' => $field, 'condition' => $condition, 'operation' => $operation]))->toArray();
            }
        }
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $condition
     * @param type $as
     * @return $this
     */
    public function addAsBoolFilter($field, $condition, $operation = "term", $as = "should") {
        $toAdd = [
            'bool' => [
                $as => []
            ]
        ];
        
        if (is_array($condition)) {
            foreach ($condition as $value) {
                $toAdd['bool'][$as][] = (new ItemCondition(['field' => $field, 'condition' => $value, 'operation' => $operation]))->toArray();
            }
        } else {
            $toAdd['bool'][$as][] = (new ItemCondition(['field' => $field, 'condition' => $condition, 'operation' => $operation]))->toArray();
        }
        $this->filter[] = $toAdd;
        return $this;
    }

    /**
     *
     * @return $this
     */
    public function resetFilter() {
        $this->filter = [];
        return $this;
    }

    /**
     * Add in the module configuration the field to use as sorting to set it as a keywork in elastic
     *
     * ```php
     * 'indexes_mapping' =>[
     *             'all' => [
     *                 "properties" => [
     *                     ...
     *                     'name_field' => ['type' => 'text', 'fields' => ['keyword' => ['type' => 'keyword']]],
     *                  ]
     *             ]
     * ],
     * ```
     *
     * Use in the ElasticModelSearch in this mode:
     * ```php
     * $this->addOrderBy([
     *          ['_score' => SORT_DESC],
     *          ['name_field.keyword' => SORT_ASC]
     *          ...
     * ]);
     * ```
     * @param type $columns
     * @return $this
     */
    public function addOrderBy($columns) {
        if (empty($columns)) {
            return [];
        }
        $orders = $this->sort;
        foreach ($columns as $name => $direction) {
            if (is_string($direction)) {
                $column = $direction;
                $direction = SORT_ASC;
            } else {
                $column = $name;
            }
            if ($this->module->versionElastic < 7) {
                if ($column == '_id') {
                    $column = '_uid';
                }
            }

            // allow Elasticsearch extended syntax as described in https://www.elastic.co/guide/en/elasticsearch/guide/master/_sorting.html
            if (is_array($direction)) {
                $orders[] = [$column => $direction];
            } else {
                $orders[] = [$column => ($direction === SORT_DESC ? 'desc' : 'asc')];
            }
        }
        $this->sort = $orders;
        return $this;
    }
    
    /**
     * 
     * @return number
     */
    public function getMustCount(){
        return count($this->must);
    }
    
    /**
     * 
     * @return number
     */
    public function getShouldCount(){
        return count($this->should);
    }

}
