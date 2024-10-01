<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190920_171225_permissions
 */
class m190920_171225_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => open20\amos\sondaggi\models\Sondaggi::WORKFLOW_STATUS_DAVALIDARE,
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ],
        ];
    }
}