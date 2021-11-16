<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\drivers
 * @category   CategoryName
 */

namespace open20\amos\utility\drivers;

use open20\amos\utility\drivers\base\bcDriver;
use open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo;
use open20\amos\organizzazioni\Module;

/**
 * 
 */
class bcDriverOrganizzazioni extends bcDriver {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->modelClassName = Module::classname(); // put here your model
        $this->widgetIconNames = [
            WidgetIconProfilo::getWidgetIconName() => WidgetIconProfilo::classname(),
        ];
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconProfilo() {
        $organizzazioniModule = \Yii::$app->getModule(Module::getModuleName());
        $modelSearch = $organizzazioniModule->createModel('ProfiloSearch');
        $this->query = $modelSearch->search([]);
    }
    
}
