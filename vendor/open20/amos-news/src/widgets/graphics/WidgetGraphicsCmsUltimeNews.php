<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\news\widgets\graphics;

use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\news\AmosNews;
use open20\amos\news\models\search\NewsSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicsCmsUltimeNews
 * @package open20\amos\news\widgets\graphics
 */
class WidgetGraphicsCmsUltimeNews extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setCode('ULTIME_NEWS_GRAPHIC');
        $this->setLabel(AmosNews::t('amosnews', '#widget_graphic_cms_last_news_label'));
        $this->setDescription(AmosNews::t('amosnews', '#widget_graphic_cms_last_news_description'));
    }
    
    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        $search = new NewsSearch();
        $search->setNotifier(new NotifyWidgetDoNothing());

        $moduleNews  = \Yii::$app->getModule(AmosNews::getModuleName());
        $newsLimit = AmosNews::MAX_LAST_NEWS_ON_DASHBOARD;
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $newsLimit = !empty($moduleNews)? $moduleNews->wgCmsUltimeNewsDashboardEngineNewLimit: 12;
        }
        
        $listaNews = $search->ultimeNews($_GET, $newsLimit);
        
        if (isset(\Yii::$app->params['showWidgetEmptyContent']) && \Yii::$app->params['showWidgetEmptyContent'] == false) {
            if ($listaNews->getTotalCount() == 0) {
                return false;
            }
        }
        
        return $this->render('@vendor/open20/amos-news/src/widgets/graphics/views/ultime_news_cms', [
            'listaNews' => $listaNews,
            'widget' => $this,
            'toRefreshSectionId' => 'widgetGraphicLatestNews'
        ]);
    }
}
