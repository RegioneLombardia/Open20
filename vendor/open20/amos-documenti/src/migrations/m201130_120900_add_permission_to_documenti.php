<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201130_120900_add_permission_to_documenti
*/
class m201130_120900_add_permission_to_documenti extends AmosMigrationPermissions
{

    protected function setRBACConfigurations()
    {

        return [
            // permesso per la pubblicazione dei documenti su frontend
            [
                'name' => 'DOCUMENTI_PUBLISHER_FRONTEND',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN', 'ADMIN_FE']
                ]
            ],
        ];
    }

}