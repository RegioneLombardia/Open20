<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\rss\widgets\graphics
 * @category   CategoryName
 */

namespace amos\rss\widgets\graphics;

use amos\rss\Module;
use amos\rss\utility\FeedUtility;
use open20\amos\core\widget\WidgetGraphic;
use Yii;

/**
 * Class WidgetGraphicsRssUltimeNews
 * @package amos\rss\widgets\graphics
 */
class WidgetGraphicsRssUltimeNews extends WidgetGraphic
{
    const IDPLATFORM = 'idplatform';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('ULTIME_NEWS_GRAPHIC');
        $this->setLabel(Module::tHtml('amosrss', '#from_other_platforms_label'));
        $this->setDescription(Module::t('amosrss', '#from_other_platforms_description'));
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        $feeds = [];
        $feedsfilter = [];
        try {
            $session = Yii::$app->session;

            $feedUtil = new FeedUtility();
            $module = Module::instance();
            $id = 0;

            $platforms = Yii::$app->request->post();

            if (!($session->has(self::IDPLATFORM) && isset($session[self::IDPLATFORM]))) {
                $session->set(self::IDPLATFORM, $id);
            }

            if (is_array($platforms) && isset($platforms['platformid'])) {
                $id = $platforms['platformid'];
                $session->set(self::IDPLATFORM, $id);
            }

            $feedsfilter[] = Yii::$app->rss->reader()->import($module->federationUrls[$session->get(self::IDPLATFORM)] . $feedUtil->getListUrl());
            foreach ($module->federationUrls as $url) {
                $feeds[] = Yii::$app->rss->reader()->import($url . $feedUtil->getListUrl());
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        } catch (\Zend\Feed\Reader\Exception\RuntimeException $zex) {
            Yii::getLogger()->log($zex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        $viewToRender = 'ultime_rss';
        $moduleLayout = \Yii::$app->getModule('layout');

        if (is_null($moduleLayout)) {
            $viewToRender = 'ultime_rss_old';
        }
        return $this->render($viewToRender, [
            'feeds' => $feeds,
            'feedsfilter' => $feedsfilter,
            'widget' => $this,
            'toRefreshSectionId' => $this->className()
        ]);
    }
}
