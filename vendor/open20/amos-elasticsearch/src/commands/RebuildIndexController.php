<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch\commands;

use open20\amos\core\record\Record;
use open20\amos\core\utilities\ClassUtility;
use Exception;
use Goutte\Client;
use open20\elasticsearch\models\ElasticIndex;
use open20\elasticsearch\models\NavItem;
use open20\elasticsearch\Module;
use ReflectionClass;
use Symfony\Component\DomCrawler\Crawler;
use Yii;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\Console;
use yii\helpers\Json;
use yii\log\Logger;

class RebuildIndexController extends Controller {

    private $baseUrl = "";
    public $client = null;
    private $_crawler = null;
    public $index_settings_name;
    public $index_name;

    public function init() {
        parent::init();
        $this->module = Module::instance();
        $this->baseUrl = \Yii::$app->params['platform']['frontendUrl'];
    }

    public function options($actionID) {
        $prms = [];
        switch ($actionID) {
            case 'open-index':
            case 'close-index':
            case 'remove-index':
                $prms = [
                    'index_name'
                ];
                break;
            case 'create-index':
            case 'set-settings':
                $prms = [
                    'index_name',
                    'index_settings_name'
                ];
                break;
            case 'set-settings-all-indexes':
            case 'create-all-indexes':
                $prms = [
                    'index_settings_name'
                ];
                break;
        }
        return $prms;
    }

    public function actionIndex() {
        echo "Help";
    }

    public function actionCreateIndex() {
        if (!is_null($this->index_settings_name) && is_array($this->module->indexes_setting)) {
            if (in_array($this->index_settings_name, $this->module->indexes_setting)) {
                // Create the index with mappings and settings now
                $results = $this->createIndex($this->index_name, $this->index_settings_name);
                var_dump($results);
            }
        }
    }

    public function actionCreateAllIndexes() {
        try {
            if ($this->module->enableI18N) {
                $this->createAllIndexesLang();
            } else {
                if (!is_null($this->index_settings_name) && is_array($this->module->indexes_setting)) {
                    if (array_key_exists($this->index_settings_name, $this->module->indexes_setting)) {
                        foreach ($this->module->modelsEnabled as $entity => $transformer) {
                            $index_name = $this->module->indexPrefixName . strtolower(ClassUtility::getClassBasename($entity));
                            $results = $this->createIndex($index_name, $this->index_settings_name, $entity);
                            var_dump($index_name);
                            var_dump($results);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    public function createAllIndexesLang() {
        try {
            if (is_array($this->module->indexes_setting)) {
                foreach ($this->module->indexes_setting as $indx => $index_settings) {
                    foreach ($this->module->modelsEnabled as $entity => $transformer) {
                        foreach (\Yii::$app->locales as $k => $v) {
                            if ($k == $indx) {
                                $index_name = $this->module->indexPrefixName . strtolower(ClassUtility::getClassBasename($entity) . '-' . $k);
                                $results = $this->createIndex($index_name, $indx, $entity);
                                var_dump($index_name);
                                var_dump($results);
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    /**
     *
     * @param string $index_name
     * @param string $index_settings_name
     * @return mixed
     */
    public function createIndex($index_name, $index_settings_name, $entity = null) {
        var_dump($this->module->indexes_setting[$index_settings_name]);
        $params = [
            'index' => $index_name,
            'body' => [
                'settings' => $this->module->indexes_setting[$index_settings_name]
            ]
        ];
        $mapping = $this->loadMappings($entity, $index_settings_name);
        if (!is_null($mapping)) {
            $params['body'][] = $mapping;
        }

        // Create the index with mappings and settings now
        $results = $this->module->client->indices()->create($params);
        return $results;
    }

    /**
     */
    public function actionOpenIndex() {
        try {
            $results = $this->openindex($this->index_name);
            var_dump($results);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    /**
     *
     * @param string $indexname
     * @return array
     */
    public function openindex($indexname) {
        $params = [
            'index' => $indexname
        ];
        $results = $this->module->client->indices()->open($params);
        return $results;
    }

    /**
     */
    public function actionCloseIndex() {
        try {
            $results = $this->closeIndex($this->index_name);
            var_dump($results);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    /**
     *
     * @param string $indexname
     */
    public function closeIndex($indexname) {
        $params = [
            'index' => $indexname
        ];
        $results = $this->module->client->indices()->close($params);
        return $results;
    }

    /**
     *
     * @param type $index
     */
    public function actionSetSettings() {
        try {
            if (!is_null($this->index_settings_name) && is_array($this->module->indexes_setting)) {
                if (array_key_exists($this->index_settings_name, $this->module->indexes_setting)) {
                    $results = $this->indexSetSettings($this->index_name, $this->index_settings_name);
                    var_dump($results);
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    /**
     */
    public function actionDisableFreeDiskControl() {
        try {
            $params = [
                'index' => '_cluster',
                'body' => [
                    'settings' => [
                        'persistent' => [
                            'cluster.routing.allocation.disk.threshold_enabled' => false
                        ]
                    ]
                ]
            ];

            $results = $this->module->client->indices()->putSettings($params);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_INFO);
            var_dump('Cluster not present');
        }
        $params = [
            'index' => '_all',
            'body' => [
                'settings' => [
                    'index.blocks.read_only_allow_delete' => null
                ]
            ]
        ];

        $results = $this->module->client->indices()->putSettings($params);
        var_dump($results);
        $params = [
            'index' => '_all'
        ];
        $results = $this->module->client->indices()->forcemerge($params);
        return $results;
    }

    /**
     */
    public function indexSetSettings($index_name, $index_settings_name) {
        $params = [
            'index' => $index_name,
            'body' => [
                'settings' => $this->module->indexes_setting[$index_settings_name]
            ]
        ];

        $results = $this->module->client->indices()->putSettings($params);
        return $results;
    }

    /**
     */
    public function actionClearAllIndexes() {
        try {
            $params = [
                "index" => "_all",
                'body' => Json::encode([
                    "query" => [
                        "match_all" => (object) null
                    ]
                ])
            ];
            $results = $this->module->client->deleteByQuery($params);
            var_dump($results);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    /**
     */
    public function actionRemoveIndex() {
        try {
            $params = [
                'index' => $this->index_name
            ];
            $results = $this->module->client->indices()->delete($params);
            var_dump($results);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    /**
     */
    public function actionRemoveAllIndexes() {
        try {
            if ($this->module->enableI18N) {
                $this->removeAllIndexesLang();
            } else {
                foreach ($this->module->modelsEnabled as $entity => $transformer) {
                    $index_name = $this->module->indexPrefixName . strtolower(ClassUtility::getClassBasename($entity));
                    $params = [
                        'index' => $index_name
                    ];
                    $results = $this->module->client->indices()->delete($params);
                    var_dump($index_name);
                    var_dump($results);
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    public function removeAllIndexesLang() {
        try {
            foreach ($this->module->modelsEnabled as $entity => $transformer) {
                foreach (\Yii::$app->locales as $k => $v) {
                    $index_name = $this->module->indexPrefixName . strtolower(ClassUtility::getClassBasename($entity) . '-' . $k);
                    $params = [
                        'index' => $index_name
                    ];
                    $results = $this->module->client->indices()->delete($params);
                    var_dump($index_name);
                    var_dump($results);
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    /**
     */
    public function actionSetSettingsAllIndexes() {
        try {
            if ($this->module->enableI18N) {
                $this->setSettingsAllIndexesLang();
            } else {
                if (!is_null($this->index_settings_name) && is_array($this->module->indexes_setting)) {
                    if (array_key_exists($this->index_settings_name, $this->module->indexes_setting)) {
                        foreach ($this->module->modelsEnabled as $entity => $transformer) {
                            $index_name = $this->module->indexPrefixName . strtolower(ClassUtility::getClassBasename($entity));

                            $results = $this->closeIndex($index_name);
                            var_dump($index_name);
                            var_dump($results);
                            $results = $this->indexSetSettings($index_name, $this->index_settings_name);
                            var_dump($index_name);
                            var_dump($results);
                            $results = $this->openIndex($index_name);
                            var_dump($index_name);
                            var_dump($results);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    public function setSettingsAllIndexesLang() {
        try {
            if (!is_null($this->index_settings_name) && is_array($this->module->indexes_setting)) {
                if (array_key_exists($this->index_settings_name, $this->module->indexes_setting)) {
                    foreach ($this->module->modelsEnabled as $entity => $transformer) {
                        foreach (\Yii::$app->locales as $k => $v) {
                            $index_name = $this->module->indexPrefixName . strtolower(ClassUtility::getClassBasename($entity) . '-' . $k);
                            $results = $this->closeIndex($index_name);
                            var_dump($index_name);
                            var_dump($results);
                            $results = $this->indexSetSettings($index_name, $this->index_settings_name);
                            var_dump($index_name);
                            var_dump($results);
                            $results = $this->openIndex($index_name);
                            var_dump($index_name);
                            var_dump($results);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            var_dump($ex->getMessage());
        }
    }

    /**
     */
    public function actionRebuild() {
        if ($this->module->enableI18N) {
            $this->RebuildLang();
        } else {
            $this->Rebuild();
        }
    }

    protected function Rebuild() {
        try {
            $limit = 100;
            foreach ($this->module->modelsEnabled as $entity => $transformer) {
                $r = new ReflectionClass($entity);
                $b = new ReflectionClass(Record::className());
                if ($r->isSubclassOf($b)) {
                    $obj = new $entity();
                    $query = (new Query())->from($entity::tableName());
                    if ($obj->hasAttribute('deleted_at')) {
                        $query->andWhere([
                            'deleted_at' => null
                        ]);
                    }
                    $number = $query->count();

                    for ($i = 0; $i <= $number / $limit; $i++) {
                        $result = $query->limit($limit)
                                ->offset($i * $limit)
                                ->all();
                        foreach ($result as $res) {
                            Console::stdout($entity);
                            Console::stdout("id:" . $res['id']);
                            $res['isNewRecord'] = false;
                            $obj = Yii::createObject($entity, [
                                        $res
                            ]);
                            $this->module->attachElasticSearchBehavior($obj);
                            $index = new ElasticIndex([
                                'model' => $obj
                            ]);
                            try {
                                if (!$index->save($index)) {
                                    Console::stdout("Not indexed >>>>........");
                                }
                            } catch (Exception $e) {
                                Yii::getLogger()->log($e->getTraceAsString(), Logger::LEVEL_ERROR);
                                Console::stdout("Not indexed >>>......error: " . $e->getMessage());
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
            Console::stdout($ex->getTraceAsString());
        }
    }

    protected function RebuildLang() {
        try {
            $limit = 100;
            foreach ($this->module->modelsEnabled as $entity => $transformer) {
                foreach (\Yii::$app->locales as $k => $v) {
                    $r = new ReflectionClass($entity);
                    $b = new ReflectionClass(Record::className());
                    if ($r->isSubclassOf($b)) {
                        $obj = new $entity();
                        $query = (new Query())->from($entity::tableName());
                        if ($obj->hasAttribute('deleted_at')) {
                            $query->andWhere([
                                'deleted_at' => null
                            ]);
                        }
                        $number = $query->count();

                        for ($i = 0; $i <= $number / $limit; $i++) {
                            $result = $query->limit($limit)
                                    ->offset($i * $limit)
                                    ->all();
                            foreach ($result as $res) {
                                Console::stdout($entity);
                                Console::stdout("id:" . $res['id']);
                                $res['isNewRecord'] = false;
                                $obj = Yii::createObject($entity, [
                                            $res
                                ]);
                                $this->module->attachElasticSearchBehavior($obj);

                                \Yii::$app->language = $k;
                                $index = new ElasticIndex([
                                    'model' => $obj,
                                    'language' => $k
                                ]);
                                try {
                                    if (!$index->save($index)) {
                                        Console::stdout("Not indexed >>>>........");
                                    }
                                } catch (Exception $e) {
                                    Yii::getLogger()->log($e->getTraceAsString(), Logger::LEVEL_ERROR);
                                    Console::stdout("Not indexed >>>......error: " . $e->getMessage());
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
            Console::stdout($ex->getMessage());
            Console::stdout($ex->getTraceAsString());
        }
    }

    /**
     */
    public function actionReIndexCms() {
        $this->RebuildIndexCms();
    }

    /**
     * 
     */
    protected function RebuildIndexCms() {
        try {
            $query = (new Query())->from(NavItem::tableName());
            foreach ($query->batch() as $i => $models) {
                foreach ($models as $model) {
                    $obj = new NavItem($model);
                    if ($obj->createUrlPreview()) {
                      
                        $path_string = $obj->getElasticUrl();                       
                        $this->module->attachElasticSearchBehavior($obj);
                        $obj->setElasticSourceText($this->getCrawlerHtml($this->baseUrl . "/" . $obj->getElastic_preview()));                      
                        if ($this->client->getInternalResponse()->getStatus() === 200) {
                            var_dump($path_string . ' - Preview: ' . $obj->getElastic_preview());
                            var_dump("id:" . $obj->id);
                            $obj->setElasticUrl($path_string);
                            $index = new ElasticIndex([
                                'model' => $obj
                            ]);
                            $index->save($index);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
            Console::stdout($ex->getTraceAsString());
        }
    }

    /**
     *
     * @param string $pageUrl
     * @return Response
     */
    protected function getCrawler($pageUrl) {
        try {
            $this->client = new Client();
            // $this->client->followRedirects(false);
            $this->_crawler = $this->client->request('GET', $pageUrl);

            if ($this->client->getInternalResponse()->getStatus() !== 200) {
                $this->_crawler = false;
            }
        } catch (\Exception $e) {
            $this->_crawler = false;
        }

        return $this->_crawler;
    }

    /**
     *
     * @param string $pageUrl
     * @return string
     */
    protected function getCrawlerHtml($pageUrl) {
        try {            
            $crawler = $this->getCrawler($pageUrl);

            if (!$crawler) {
                return '';
            }

            $crawler->filter('nav')->each(function (Crawler $crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });
            $crawler->filter('footer')->each(function (Crawler $crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });

            $crawler->filter('script')->each(function (Crawler $crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });

            $crawler->filter('style')->each(function (Crawler $crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });

            $class = $crawler->filter('ul')->attr('class');
            if (strpos($class, 'navbar') !== false) {
                $crawler->filter('ul')->each(function (Crawler $crawler) {
                    foreach ($crawler as $node) {
                        $node->parentNode->removeChild($node);
                    }
                });
            }
            $elasticSearchModule = Yii::$app->getModule('elasticsearch');
            $cssFilter = $elasticSearchModule->cssGetCrawlerHtmlFilter;
            return preg_replace('/\s\s+/', ' ', strip_tags($crawler->filter($cssFilter)->html()));
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     *
     * @param ActiveRecord $entity
     */
    private function loadMappings($entity, $index_settings_name) {
        $result = null;
        $index_type_mapping = ElasticIndex::purifyIndexType($entity);
        if (isset($this->module->indexes_mapping[$index_settings_name])) {
            $result['mappings'][$index_type_mapping] = $this->module->indexes_mapping[$entity];
        } else {
            if (isset($this->module->indexes_mapping['all'])) {
                $result['mappings'][$index_type_mapping] = $this->module->indexes_mapping['all'];
            }
        }
        return $result;
    }

}
