<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m181018_134854_add_admin_tag_tabs_permission
 */
class m181018_134854_add_admin_tag_tabs_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'FORM_TAG_TABS_PERMISSION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => "Permesso per vedere la tab dei tag nella form di modifica dell'utente",
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => 'VIEW_TAG_TABS_PERMISSION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => "Permesso per vedere la tab dei tag nella scheda in sola lettura dell'utente",
                'parent' => ['ADMIN', 'BASIC_USER']
            ]
        ];
    }
}
