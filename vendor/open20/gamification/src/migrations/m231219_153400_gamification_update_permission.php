<?php
use open20\amos\core\migration\AmosMigrationPermissions;


/**
 * Class m231219_153400_gamification_update_permission */
class m231219_153400_gamification_update_permission extends AmosMigrationPermissions
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
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'SUPERUSER'
                    ]
                ]
            ],
        ];
    }
}
