<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\widgets;

use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use yii\data\ActiveDataProvider;

/**
 * Class WidgetGraphicsUltimeNews
 * @package open20\amos\news\widgets\graphics
 */
class WidgetGraphicsUltimeNews extends WidgetGraphic
{
    public $id;
    
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
    
    public function run(){
        return $this-> getHtml();
    }
    
    
    /**
     * @@inheritdoc
     */
    public function getHtml()
    {
       $search = AmosNews::instance()->createModel('NewsSearch');
        $search->setNotifier(new NotifyWidgetDoNothing());
        
        $viewPath = '@vendor/open20/amos-organizzazioni/src/widgets/views/';
        $viewToRender = $viewPath . 'ultime_news';
        
        $newsLimit = AmosNews::MAX_LAST_NEWS_ON_DASHBOARD;
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $newsLimit = 12;
        }
        $listaNews = null;
        if ($this->id) {
            $query = News::find()->distinct();
            $cwhConfig = CwhConfig::find()->andWhere(['tablename' => 'profilo'])->one();
            if ($cwhConfig) {
                $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery(
                    News::className(),
                    [
                        'queryBase' => $query,
                        'bypassScope' => false
                    ]
                    );
                $query = $cwhActiveQuery->getQueryCwhAll($cwhConfig->id, $this->id, false);
            }
            $dp_params = ['query' => $query,];
            //set the data provider
            $listaNews = new ActiveDataProvider($dp_params);
        }
        
        
        
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
