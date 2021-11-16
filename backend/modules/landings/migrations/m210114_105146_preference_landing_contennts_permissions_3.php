<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m210114_105146_preference_landing_contennts_permissions_3*/
class m210114_105146_preference_landing_contennts_permissions_3 extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'AMMINISTRATORE_DOCUMENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
            [
                'name' => 'DOCUMENTI_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
            [
                'name' => 'DOCUMENTI_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
            [
                'name' => 'DOCUMENTI_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
            [
                'name' => 'DOCUMENTI_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],


        ];
    }
}
