<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics
 * @category   CategoryName
 */

namespace amos\rss\widgets\graphics;

use amos\rss\Module;
use open20\amos\core\widget\WidgetGraphic;
use Exception;
use Yii;

/**
 * Class WidgetGraphicsRssNewsCategory
 * @package amos\rss\widgets\graphics;
 */
class WidgetGraphicsRssNewsCategory extends WidgetGraphic {

    /**
     * @var array $availableViews
     */
    public $availableViews;

    /**
     * @var int $parentId
     */
    public $parentId = null;

    /**
     * @var Documenti $parent
     */
    public $parent = null;

    /**
     * @var string
     */
    public $viewToRender = 'news_category';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->setCode('NEWS_CATEGORY_GRAPHICS');
        $this->setLabel(Module::tHtml('amosrss', '#news_categories'));
        $this->setDescription(Module::t('amosrss', '#elenca_le_ultime_rss'));
        $this->setClassFullSize('grid-item-fullsize');		
    }

    /**
     * 
     * @return type
     */
    public function getHtml() {
        $viewToRender = $this->viewToRender;
//        $labels = array();
        $feeds = array();
        $result = array();
        $feedsUrls = array();
        $title = '';
        $notTitle = FALSE;
        $link = array();

        try {
            $module = Module::instance();
            $feedsUrls = $this->getUriFromCwhScope();
            if (!isset($feedsUrls) || empty($feedsUrls)) {
                $feedsUrls = $module->feedsUrls;
            }  
            foreach ($feedsUrls as $uri) {
                $feed = Yii::$app->rss->reader()->import($uri);
                if (!empty($title) && ($title != $feed->getTitle())) {
                    $notTitle = TRUE;
                }
                $title = $feed->getTitle();
                $feeds[] = $feed;

                if (isset($module->category)) {
                    $result[$module->category][] = $uri;
                } else {
                    $categories = $feed->getCategories();
                    foreach ($categories as $cat) {
                        $urlcat = $cat['scheme'];
                        $result[$cat['label']] = [$urlcat];
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        } catch (\Zend\Feed\Reader\Exception\RuntimeException $zex) {
            Yii::getLogger()->log($zex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }



        $title = $notTitle ? '' : $title;
        return $this->render($viewToRender, [
                    'title' => $title,
                    'feeds' => $feeds,
                    'feedsfilter' => $result,
                    'category' => isset($module->category) ? $module->category : null,
                    'widget' => $this,
                    'toRefreshSectionId' => $this->className()
        ]);
    }

    /**
     * @return ActiveQuery
     */
    private function getUriFromCwhScope() {
        $feedsUrls = array();
        $moduleCwh = \Yii::$app->getModule('cwh');
        $module = Module::instance();

        if (isset($moduleCwh)) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            $moduleCwh->setCwhScopeFromSession();
            if (isset($moduleCwh->scope) && !empty($moduleCwh->scope)) {
                foreach ($module->feedsScope as $scope => $urls) {
                    if (isset($urls) && !empty($urls)) {
//                        $urls = $module->feedsUrls[$moduleCwh->scope];
                        foreach ($urls as $url => $id) {
                            $entityId = $moduleCwh->userEntityRelationTable['entity_id'];
                            if (in_array($entityId, $id)) {
                                $feedsUrls[] = $url;
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $feedsUrls;
    }

/**
     * 
     * @return string
     */
    public function run()
    {
        return $this->getHtml();
    }
}
