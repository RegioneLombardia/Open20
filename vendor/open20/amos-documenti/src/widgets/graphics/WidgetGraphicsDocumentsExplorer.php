<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgetRs\graphics
 * @category   CategoryName
 */

namespace open20\amos\documenti\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\documenti\AmosDocumenti;

class WidgetGraphicsDocumentsExplorer extends WidgetGraphic
{
    /**
     * @inheritdocF
     */
    public function init()
    {
        parent::init();

        $this->setCode('DOCUMENTS_EXPLORER_GRAPHIC');
        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', 'Documenti'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', 'Naviga tra i documenti'));
    }

    public function getHtml()
    {
        return $this->render('documents-explorer/documents_explorer');
    }
}