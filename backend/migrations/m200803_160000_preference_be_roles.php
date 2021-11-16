<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200803_160000_preference_be_roles*/
class m200803_160000_preference_be_roles extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'PC_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Preference Centre Amministratore',
                'ruleName' => null,
                'parent' => [],
            ],
            [
                'name' => 'PC_EDITOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Preference Centre Operatore',
                'ruleName' => null,
                'parent' => [],
            ],
            [
                'name' => 'PC_APPROVER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Preference Centre approvatore',
                'ruleName' => null,
                'parent' => ['PC_EDITOR'],
            ],

            [
                'name' => 'PC_STRUCTURE_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Preference Centre Amministratore di struttura',
                'ruleName' => null,
                'parent' => [],
            ],
            [
                'name' => 'PC_STRUCTURE_EDITOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Preference Centre Operatore di struttura',
                'ruleName' => null,
                'parent' => [],
            ],
            [
                'name' => 'PC_STRUCTURE_APPROVER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Preference Centre Approvatore di struttura',
                'ruleName' => null,
                'parent' => ['PC_STRUCTURE_EDITOR'],
            ],     
            
            [
                'name' => 'PC_BASIC_USER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Preference Centre utente base, funzionalità OPEN 2.0',
                'ruleName' => null,
                'parent' => ['PC_ADMIN', 'PC_EDITOR', 'PC_APPROVER', 'PC_STRUCTURE_ADMIN', 'PC_STRUCTURE_EDITOR', 'PC_STRUCTURE_APPROVER'],
            ],  
           
        ];
    }
}
