<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsByCategory;
use yii\rbac\Permission;

/**
 * Class m200324_095213_add_widget_graphic_documents_by_category_permission
 */
class m200324_095213_add_widget_graphic_documents_by_category_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => WidgetGraphicsDocumentsByCategory::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permissions for the dashboard for the widget WidgetGraphicDocumentsByCategory',
                'parent' => ['LETTORE_DOCUMENTI']
            ]
        ];
    }
}
