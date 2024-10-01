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
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class WidgetGraphicsUltimiDocumenti
 * @package open20\amos\documenti\widgets\graphics
 */
class WidgetGraphicsUltimiDocumenti extends WidgetGraphic
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
     * @inheritdoc
     */
    public function init()
    {
        $this->widgetTitle = AmosDocumenti::tHtml('amosdocumenti', 'Documenti');
        $this->linkReadAll = ['/documenti'];

        parent::init();

        $this->setCode('ULTIME_DOCUMENTI_GRAPHIC');
        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', 'Ultimi documenti'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', 'Elenca gli ultimi documenti'));
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        $listaDocumenti = $this->getDataProvider();

        $moduleL = \Yii::$app->getModule('layout');
        $viewPath = '@vendor/open20/amos-documenti/src/widgets/graphics/views/';
        $viewToRender = $viewPath . 'ultimi_documenti';

        if (empty($moduleL)) {
            $viewToRender .= '_old';
        }

        return $this->render($viewToRender, [
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
        $listaDocumenti = $search->lastDocuments($_GET, 3);
        if (!empty($this->filterDocumentCategoryId)) {
            $listaDocumenti->query->andWhere([Documenti::tableName() . '.documenti_categorie_id' => $this->filterDocumentCategoryId]);
        }
        return $listaDocumenti;
    }
}
