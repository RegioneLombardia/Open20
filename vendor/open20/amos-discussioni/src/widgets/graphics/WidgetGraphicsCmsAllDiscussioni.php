<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\search\DiscussioniTopicSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class WidgetGraphicsUltimeDiscussioni
 * informational widget that lists the latest discussions
 *
 * @package open20\amos\discussioni\widgets\graphics
 */
class WidgetGraphicsCmsAllDiscussioni extends WidgetGraphic
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('ULTIME_DISCUSSIONI_GRAPHIC');
        $this->setLabel(AmosDiscussioni::tHtml('amosdiscussioni', 'Discussioni'));
        $this->setDescription(AmosDiscussioni::t('amosdiscussioni', 'Elenca le discussioni'));
    }

    /**
     * Rendering of the view ultime_discussioni
     *
     * @return string
     */
    public function getHtml()
    {
        $search     = new DiscussioniTopicSearch();
        $search->setNotifier(new NotifyWidgetDoNothing());
        $listaTopic = $search->searchAll($_GET);

        $viewPath     = '@vendor/open20/amos-discussioni/src/widgets/graphics/views/';
        $viewToRender = $viewPath.'all_discussioni_cms';
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