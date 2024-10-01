<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\search\SondaggiSearch;

/**
 * Class WidgetGraphicsUltimiSondaggi
 * @package open20\amos\sondaggi\widgets\graphics
 */
class WidgetGraphicsUltimiSondaggi extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setCode('ULTIMI_SONDAGGI_GRAPHIC');
        $this->setLabel(AmosSondaggi::t('amossondaggi', 'Ultimi sondaggi'));
        $this->setDescription(AmosSondaggi::t('amossondaggi', '#widget_graphic_cms_last_surveys_description'));
    }
    
    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        $search = new SondaggiSearch();
        $search->setNotifier(new NotifyWidgetDoNothing());
        
        $listaSondaggi = $search->ultimiSondaggi($_GET, 3);
        
        if (isset(\Yii::$app->params['showWidgetEmptyContent']) && \Yii::$app->params['showWidgetEmptyContent'] == false) {
            if ($listaSondaggi->getTotalCount() == 0) {
                return false;
            }
        }
        
        return $this->render('ultimi_sondaggi_cms', [
            'lista' => $listaSondaggi,
            'widget' => $this,
            'toRefreshSectionId' => 'widgetGraphicSondaggi'
        ]);
    }
}
