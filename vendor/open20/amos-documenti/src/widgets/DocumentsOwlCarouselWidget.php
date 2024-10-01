<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets
 * @category   CategoryName
 */

namespace open20\amos\documenti\widgets;

use open20\amos\core\forms\AmosOwlCarouselWidget;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\db\ActiveQuery;

/**
 * Class DocumentsOwlCarouselWidget
 * @package open20\amos\documenti\widgets
 */
class DocumentsOwlCarouselWidget extends AmosOwlCarouselWidget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setItems($this->getDocumentsItems());

        parent::init();
    }

    /**
     * @return array
     */
    protected function getDocumentsItems()
    {
        $documentsHighlights = [];
        $highlightsModule = \Yii::$app->getModule('highlights');

        /** @var AmosDocumenti $documentsModule */
        $documentsModule = AmosDocumenti::instance();

        if (!is_null($highlightsModule)) {
            /** @var \amos\highlights\Module $highlightsModule */
            $documentsHighlightsIds = $highlightsModule->getHighlightedContents($documentsModule->model('Documenti'));
            /** @var Documenti $documentiModel */
            $documentiModel = $documentsModule->createModel('Documenti');
            /** @var ActiveQuery $query */
            $query = $documentiModel::find();
            $query->distinct();
            $query->andWhere(['id' => $documentsHighlightsIds]);
            $query->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO]);
            $query->andWhere(['or',
                ['data_rimozione' => null],
                ['>=', 'data_rimozione', date('Y-m-d')]
            ]);
            $documentsHighlights = $query->all();
        }

        return $documentsHighlights;
    }
}
