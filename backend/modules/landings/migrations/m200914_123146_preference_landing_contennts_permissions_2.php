<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200910_113646_preference_landing_protagonist_permissions*/
class m200914_123146_preference_landing_contennts_permissions_2 extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'SITEMANAGEMENTSLIDERELEM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
            [
                'name' => 'SITEMANAGEMENTSLIDERELEM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
            [
                'name' => 'SITEMANAGEMENTSLIDERELEM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTSLIDERELEM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
//-----------------------------------------------
            [
                'name' => 'NEWS_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
            [
                'name' => 'NEWS_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
            [
                'name' => 'NEWS_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],
            [
                'name' => 'NEWS_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_LANDING_ADMINISTRATOR']
                ]],


        ];
    }
}
