<?php

namespace backend\modules\campains\widgets\graphics;

use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\models\search\PreferenceCampainSearch;
use backend\modules\campains\Module;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetGraphic;

use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

class WidgetGraphicsLastCampains extends WidgetGraphic
{
    const MAX_LAST_CAMPAINS_ON_DASHBOARD =2;
    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->setCode('LAST_CAMPAINS_WIDGET');
        $this->setLabel(Module::tHtml('campains', 'Ultime campagne'));
        $this->setDescription(Module::t('campains', 'Ultime campagne'));
    }

    /**
     *
     * @return type@inheritdoc
     */
    public function getHtml() {
        $search = new PreferenceCampainSearch();
        $search->setNotifier(new NotifyWidgetDoNothing());

        $viewPath = '@backend/modules/campains/widgets/graphics/views/';
        $viewToRender = $viewPath . 'last_campains';

        //$newsLimit = self::MAX_LAST_CAMPAINS_ON_DASHBOARD;

        /*
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
          $newsLimit = 12;
        }
        */

        $campainsLimit = self::MAX_LAST_CAMPAINS_ON_DASHBOARD;

        $listCampains= $search->search($_GET, $campainsLimit);

        $moduleLayout = \Yii::$app->getModule('layout');
//        if (is_null($moduleLayout)) {
//            $viewToRender .= '_old';
//        }

        return $this->render(
            $viewToRender,
            [
                'listCampains' => $listCampains,
                'widget' => $this,
                'toRefreshSectionId' => 'WidgetGraphicsLastCampains'
            ]
        );
    }
}