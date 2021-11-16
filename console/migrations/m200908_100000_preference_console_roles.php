<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200908_100000_preference_console_roles */
class m200908_100000_preference_console_roles extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'PC_CRON',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Preference Centre CRON',
                'ruleName' => null,
                'parent' => [],
            ],           
        ];
    }
}
