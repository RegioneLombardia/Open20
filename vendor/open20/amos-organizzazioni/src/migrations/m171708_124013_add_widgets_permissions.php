<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazzioni
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m171708_124013_add_widgets_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['PROFILO_READ']
            ]
        ];
    }
}
