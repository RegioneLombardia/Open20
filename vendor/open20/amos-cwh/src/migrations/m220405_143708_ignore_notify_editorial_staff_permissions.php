<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m220403_235308_plan_work_platform_packages_permissions*/
class m220405_143708_ignore_notify_editorial_staff_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'IGNORE_NOTIFY_EDITORIAL_STAFF',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso per vedere il checkbox ignora nnotify_editorial_staff',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
