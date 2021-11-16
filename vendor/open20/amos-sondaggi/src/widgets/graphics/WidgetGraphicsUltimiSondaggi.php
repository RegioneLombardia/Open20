<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use open20\amos\core\widget\WidgetAbstract;

class WidgetGraphicsUltimiSondaggi extends WidgetGraphic {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->setCode('ULTIMI_SONDAGGI_GRAPHIC');
        $this->setLabel(AmosSondaggi::tHtml('amossondaggi', 'Ultimi sondaggi'));
        $this->setDescription(AmosSondaggi::t('amossondaggi', 'Elenca gli ultimi sondaggi'));
    }

    /**
     * 
     * @return string
     */
    public function getHtml() {
        $search = new SondaggiSearch();
        $search->setNotifier(new NotifyWidgetDoNothing());


        $listaSondaggi = $search->ultimiSondaggi($_GET, 3);
        $viewToRender = 'ultimi_sondaggi';
        $viewToRender = 'ultimi_sondaggi_cms';

        return $this->render($viewToRender, [
                    'lista' => $listaSondaggi,
                    'widget' => $this,
                    'toRefreshSectionId' => 'widgetGraphicSondaggi'
        ]);
    }

}
