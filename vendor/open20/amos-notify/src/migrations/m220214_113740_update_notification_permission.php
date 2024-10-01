<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m191021_163740_notification_content_language_permissions*/
class m220214_113740_update_notification_permission extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'RESEND_NOTIFICATION',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso per reinviare la notifica di un contenuto',
                    'ruleName' => null,
                    'parent' => ['NOTIFY_ADMINISTRATOR']
                ],


            ];
    }
}
