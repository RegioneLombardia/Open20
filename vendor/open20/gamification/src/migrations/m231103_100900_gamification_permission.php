<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m231103_100900_gamification_permission */
class m231103_100900_gamification_permission extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'GAMIFICATION_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di AMMINISTRATORE sul model gamification',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'GAMIFICATION_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model gamification',
                'ruleName' => null,
                'parent' => ['GAMIFICATION_ADMINISTRATOR']
            ],
            [
                'name' =>  'GAMIFICATION_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model gamification',
                'ruleName' => null,
                'parent' => ['GAMIFICATION_ADMINISTRATOR']
            ],
            [
                'name' =>  'GAMIFICATION_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model gamification',
                'ruleName' => null,
                'parent' => ['GAMIFICATION_ADMINISTRATOR']
            ],
            [
                'name' =>  'GAMIFICATION_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model gamification',
                'ruleName' => null,
                'parent' => ['GAMIFICATION_ADMINISTRATOR']
            ],

        ];
    }
}
