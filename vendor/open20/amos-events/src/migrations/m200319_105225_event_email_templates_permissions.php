<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200319_105225_event_email_templates_permissions*/
class m200319_105225_event_email_templates_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'EVENTEMAILTEMPLATES_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model EventEmailTemplates',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTEMAILTEMPLATES_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model EventEmailTemplates',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'EVENTEMAILTEMPLATES_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model EventEmailTemplates',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTEMAILTEMPLATES_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model EventEmailTemplates',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                ],

            ];
    }
}
