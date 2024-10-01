<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch\utilities;

use Exception;
use Goutte\Client;
use open20\elasticsearch\models\ElasticIndex;
use open20\elasticsearch\models\NavItem;
use open20\elasticsearch\Module;
use Symfony\Component\DomCrawler\Crawler;
use Yii;
use yii\log\Logger;

class Utility {

    private $baseUrl = "";
    public $client = null;
    private $_crawler = null;
    public $index_settings_name;
    public $index_name;

    public function __construct() {
        $this->module = Module::instance();
        $this->baseUrl = \Yii::$app->params['platform']['frontendUrl'];
    }

    /**
     * 
     * @param NavItem $obj
     */
    public function rebuildCmsIndex($obj) {
        try {
            if ($obj->createUrlPreview()) {
                $path_string = $obj->elasticUrl;
                $this->module->attachElasticSearchBehavior($obj);
                $obj->setElasticSourceText($this->getCrawlerHtml($this->baseUrl . "/" . $obj->getElastic_preview()));
                if ($this->client->getInternalResponse()->getStatus() === 200) {
                    $obj->setElasticUrl($path_string);
                    $index = new ElasticIndex([
                        'model' => $obj
                    ]);
                    $index->save($index);
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
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
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
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
}
