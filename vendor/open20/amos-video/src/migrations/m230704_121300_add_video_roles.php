<?php

use yii\rbac\Permission;

class m230704_121300_add_video_roles extends \open20\amos\core\migration\AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';
        return [
            [
                'name' => 'VIDEO_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di amministrare il modulo video',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                    'AMMINISTRATORE_COMMUNITY'
                ]
            ],
            [
                'name' => 'VIDEO_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di vedere il modulo video',
                'ruleName' => null,
                'parent' => [
                    'BASIC_USER',
                    'COMMUNITY_READER'
                ]
            ]
        ];
    }
}