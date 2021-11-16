<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200923_160000_preference_landing_contennts_permissions_3*/
class m200923_160000_preference_landing_contennts_permissions_3 extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
           
            [
                'name' => 'VALIDATORE_NEWS',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
    


        ];
    }
}
