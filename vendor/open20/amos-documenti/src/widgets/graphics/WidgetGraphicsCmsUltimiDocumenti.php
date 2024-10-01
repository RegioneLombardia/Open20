<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\documenti\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicsCmsUltimiDocumenti
 * @package open20\amos\documenti\widgets\graphics
 */
class WidgetGraphicsCmsUltimiDocumenti extends WidgetGraphic
{
    /**
     * @var array $filterDocumentCategoryId
     */
    public $filterDocumentCategoryId = [];
    
    /**
     * @var string $widgetTitle
     */
    public $widgetTitle = '';
    
    /**
     * @var string|array $linkReadAll
     */
    public $linkReadAll = '';
    
    /**
     * @var bool
     */
    public $excludeFolders = false;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->widgetTitle = AmosDocumenti::tHtml('amosdocumenti', 'Documenti');
        $this->linkReadAll = ['/documenti'];
        /** @var AmosDocumenti $documentsModule */
        $documentsModule = AmosDocumenti::instance();
        $this->excludeFolders = $documentsModule->excludeFoldersInWGCmsUltimiDocumenti;
        
        parent::init();
        
        $this->setCode('ULTIME_DOCUMENTI_GRAPHIC');
        $this->setLabel(AmosDocumenti::t('amosdocumenti', '#widget_graphic_cms_last_documents_label'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#widget_graphic_cms_last_documents_description'));
    }
    
    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        $listaDocumenti = $this->getDataProvider();
        
        if (isset(\Yii::$app->params['showWidgetEmptyContent']) && \Yii::$app->params['showWidgetEmptyContent'] == false) {
            if ($listaDocumenti->getTotalCount() == 0) {
                return false;
            }
        }
        
        return $this->render('@vendor/open20/amos-documenti/src/widgets/graphics/views/ultimi_documenti_cms', [
            'listaDocumenti' => $listaDocumenti,
            'widget' => $this,
            'toRefreshSectionId' => 'widgetGraphicLatestDocumenti'
        ]);
    }
    
    /**
     * Returns the widget data provider.
     * @return \yii\data\ActiveDataProvider
     */
    protected function getDataProvider()
    {
        /** @var DocumentiSearch $search */
        $search = AmosDocumenti::instance()->createModel('DocumentiSearch');
        $search->setNotifier(new NotifyWidgetDoNothing());
        
        $documentsModule = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        if($documentsModule->showAllStatusesAllDocument) {
           $listaDocumenti = $search->searchAllInAllStatuses($_GET, 3); 
        } else {
            $listaDocumenti = $search->lastDocuments($_GET, 3);
        }
        if (!empty($this->filterDocumentCategoryId)) {
            $listaDocumenti->query->andWhere([Documenti::tableName() . '.documenti_categorie_id' => $this->filterDocumentCategoryId]);
        }
        if ($this->excludeFolders) {
            $listaDocumenti->query->andWhere([Documenti::tableName() . '.is_folder' => false]);
        }
        return $listaDocumenti;
    }
}
