<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180919_105859_create_widget_community_WidgetIconAccademyDocument_permissions
 */
class m180919_105859_create_widget_community_WidgetIconAccademyDocument_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconAccademyDocument::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession to view the widget for download accademy document.',
                'parent' => ['AMMINISTRATORE_COMMUNITY', 'BASIC_USER']
            ]
        ];
    }
}
