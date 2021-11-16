<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

class m210212_112840_add_role_redactor_documents extends AmosMigrationPermissions
{

    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'REDACTOR_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo Redattore documenti',
            ],
            [
                'name' => 'DocumentRedactorOnDomainRule',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission custon content type',
                'ruleName' => \open20\amos\documenti\rules\DocumentRedactorOnDomainRule::className(),
                'parent' => ['REDACTOR_DOCUMENTI'],
                'children' => [
                    'DOCUMENTI_CREATE',
                    'DOCUMENTI_READ',
                    'DOCUMENTI_DELETE',
                    'DOCUMENTI_UPDATE',
                ]
            ],
        ];
    }

}