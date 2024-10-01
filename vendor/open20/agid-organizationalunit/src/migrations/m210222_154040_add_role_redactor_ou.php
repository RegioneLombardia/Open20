<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

class m210222_154040_add_role_redactor_ou extends AmosMigrationPermissions
{

    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'REDACTOR_ORGANIZATIONALUNIT',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo Redattore organizationalunit',
            ],
            [
                'name' => 'OrganizationalunitRedactorOnDomainRule',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission custon content type',
                'ruleName' => \open20\agid\organizationalunit\rules\OrganizationalunitRedactorOnDomainRule::className(),
                'parent' => ['REDACTOR_ORGANIZATIONALUNIT'],
                'children' => [
                    'AGIDORGANIZATIONALUNIT_CREATE',
                    'AGIDORGANIZATIONALUNIT_READ',
                    'AGIDORGANIZATIONALUNIT_DELETE',
                    'AGIDORGANIZATIONALUNIT_UPDATE',
                ]
            ],
        ];
    }

}