<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\migrations
 * @category   Migration
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170615_160804_add_report_permission_to_basic_user
 */
class m170615_160804_add_report_permission_to_basic_user extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'REPORT_CONTRIBUTOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ]
        ];
    }
}
