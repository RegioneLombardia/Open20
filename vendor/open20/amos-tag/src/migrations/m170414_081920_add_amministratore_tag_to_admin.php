<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170414_081920_add_amministratore_tag_to_admin
 */
class m170414_081920_add_amministratore_tag_to_admin extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_TAG',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore tag',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}
