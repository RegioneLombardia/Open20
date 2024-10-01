<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\sondaggi\models\Sondaggi;
use yii\rbac\Permission;

/**
 * Class m180910_103528_create_sondaggi_workflow_permissions
 */
class m180910_103528_create_sondaggi_workflow_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => Sondaggi::WORKFLOW_STATUS_BOZZA,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso stato workflow Sondaggi: Bozza',
                'parent' => ['AMMINISTRAZIONE_SONDAGGI']
            ],
            [
                'name' => Sondaggi::WORKFLOW_STATUS_DAVALIDARE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso stato workflow Sondaggi: Da validare',
                'parent' => ['AMMINISTRAZIONE_SONDAGGI']
            ],
            [
                'name' => Sondaggi::WORKFLOW_STATUS_VALIDATO,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso stato workflow Sondaggi: Validato',
                'parent' => ['AMMINISTRAZIONE_SONDAGGI']
            ]
        ];
    }
}
