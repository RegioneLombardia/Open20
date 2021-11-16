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
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\documenti\widgets\icons\WidgetIconAdminAllDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconAllDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiCreatedBy;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare;

/**
 * 
 */
class bcDriverDocumenti extends bcDriver
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName  = Documenti::className(); // put here your model
        $this->widgetIconNames = [
//            WidgetIconAdminAllDocumenti::getWidgetIconName() => WidgetIconAdminAllDocumenti::classname(),
            WidgetIconDocumenti::getWidgetIconName() => WidgetIconDocumenti::classname(),
            WidgetIconAllDocumenti::getWidgetIconName() => WidgetIconAllDocumenti::classname(),
//            WidgetIconDocumentiCreatedBy::getWidgetIconName() => WidgetIconDocumentiCreatedBy::classname(),
//            WidgetIconDocumentiDaValidare::getWidgetIconName() => WidgetIconDocumentiDaValidare::classname(),
        ];
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconAdminAllDocumenti()
    {
        $search      = new DocumentiSearch();
        $this->query = $search->buildQuery([], 'admin-all');
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconDocumentiCreatedBy()
    {
        $search      = new DocumentiSearch();
        $this->query = $search->searchCreatedByMeQuery([]);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconDocumentiDaValidare()
    {
        $search      = new DocumentiSearch();
        $this->query = $search->searchToValidateQuery([]);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconAllDocumenti()
    {
        $this->query = $this->cwhActiveQuery->getQueryCwhAll();
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconDocumenti()
    {
        $this->query = $this->cwhActiveQuery->getQueryCwhOwnInterest();
    }
}