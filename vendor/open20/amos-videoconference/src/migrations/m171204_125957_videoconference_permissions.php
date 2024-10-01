<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m171204_125957_videoconference_permissions
 */
class m171204_125957_videoconference_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc 
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'AMMINISTRATORE_VIDEOCONF',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di Amministratore del plugin di Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'JoinOwnVideoconference',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Videoconf',
                'ruleName' => \open20\amos\videoconference\rules\JoinOwnVideoconference::className(),
                'parent' => ['ADMIN', 'BASIC_USER', 'AMMINISTRATORE_VIDEOCONF']
            ],
            [
                'name' => 'UpdateOwnVideoconference',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Videoconf',
                'ruleName' => \open20\amos\videoconference\rules\UpdateOwnVideoconference::className(),
                'parent' => ['ADMIN', 'BASIC_USER', 'AMMINISTRATORE_VIDEOCONF']
            ],
            [
                'name' => 'VIDEOCONF_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di creazione di una Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN', 'AMMINISTRATORE_VIDEOCONF']
            ],
            [
                'name' => 'VIDEOCONF_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di partecipare ad una Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN', 'AMMINISTRATORE_VIDEOCONF', 'JoinOwnVideoconference']
            ],
            [
                'name' => 'VIDEOCONF_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modificare una Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN', 'AMMINISTRATORE_VIDEOCONF', 'UpdateOwnVideoconference']
            ],
            [
                'name' => 'VIDEOCONF_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di cancellare una Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN', 'AMMINISTRATORE_VIDEOCONF', 'UpdateOwnVideoconference']
            ],
        ];
    }
}