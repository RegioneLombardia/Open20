<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsByCategory;

/**
 * Class m200323_185639_add_widget_graphic_documents_by_category
 */
class m200323_185639_add_widget_graphic_documents_by_category extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetGraphicsDocumentsByCategory::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_DISABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 50
            ]
        ];
    }
}
