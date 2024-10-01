<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\discussioni\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\search\DiscussioniTopicSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicsCmsUltimeDiscussioni
 * informational widget that lists the latest discussions
 *
 * @package open20\amos\discussioni\widgets\graphics
 */
class WidgetGraphicsCmsUltimeDiscussioni extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setCode('ULTIME_DISCUSSIONI_GRAPHIC');
        $this->setLabel(AmosDiscussioni::t('amosdiscussioni', '#widget_graphic_cms_last_discussions_label'));
        $this->setDescription(AmosDiscussioni::t('amosdiscussioni', '#widget_graphic_cms_last_discussions_description'));
    }
    
    /**
     * Rendering of the view ultime_discussioni
     *
     * @return string
     */
    public function getHtml()
    {
        $search = new DiscussioniTopicSearch();
        $search->setNotifier(new NotifyWidgetDoNothing());
        $listaTopic = $search->ultimeDiscussioni($_GET, AmosDiscussioni::MAX_LAST_DISCUSSION_ON_DASHBOARD);
        
        $viewPath = '@vendor/open20/amos-discussioni/src/widgets/graphics/views/';
        $viewToRender = $viewPath . 'ultime_discussioni_cms';
        if (is_null(\Yii::$app->getModule('layout'))) {
            $viewToRender .= '_old';
        }
        
        if (isset(\Yii::$app->params['showWidgetEmptyContent']) && \Yii::$app->params['showWidgetEmptyContent'] == false) {
            if ($listaTopic->getTotalCount() == 0) {
                return false;
            }
        }
        
        return $this->render(
            $viewToRender,
            [
                'listaTopic' => $listaTopic,
                'widget' => $this,
                'toRefreshSectionId' => 'widgetGraphicLatestThreads'
            ]
        );
    }
}
