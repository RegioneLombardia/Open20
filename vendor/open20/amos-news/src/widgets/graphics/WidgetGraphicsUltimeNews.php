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
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicsUltimeNews
 * @package open20\amos\news\widgets\graphics
 */
class WidgetGraphicsUltimeNews extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setCode('ULTIME_NEWS_GRAPHIC');
        $this->setLabel(AmosNews::tHtml('amosnews', 'Ultime news'));
        $this->setDescription(AmosNews::t('amosnews', 'Elenca le ultime news'));
    }
    
    /**
     * @@inheritdoc
     */
    public function getHtml()
    {
       $search = AmosNews::instance()->createModel('NewsSearch');
        $search->setNotifier(new NotifyWidgetDoNothing());
        
        $viewPath = '@vendor/open20/amos-news/src/widgets/graphics/views/';
        $viewToRender = $viewPath . 'ultime_news';
        
        $newsLimit = AmosNews::MAX_LAST_NEWS_ON_DASHBOARD;
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $newsLimit = 12;
        }
        
        $listaNews = $search->ultimeNews($_GET, $newsLimit);
        
        $moduleLayout = \Yii::$app->getModule('layout');
        if (is_null($moduleLayout)) {
            $viewToRender .= '_old';
        }
        
        return $this->render(
            $viewToRender,
            [
                'listaNews' => $listaNews,
                'widget' => $this,
                'toRefreshSectionId' => 'widgetGraphicLatestNews'
            ]
        );
    }
}
