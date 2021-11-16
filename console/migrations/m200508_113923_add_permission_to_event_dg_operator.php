<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m191003_121523_event_seats_permissions*/
class m200508_113923_add_permission_to_event_dg_operator extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'AMMINISTRATORE_UTENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG_OPERATOR'],
                ]
            ],
            [
                'name' => 'open20\amos\admin\widgets\icons\WidgetIconAdmin',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG_OPERATOR'],
                ]
            ],


        ];

    }
}
