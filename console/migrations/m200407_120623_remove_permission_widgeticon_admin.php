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
class m200407_120623_remove_permission_widgeticon_admin extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'open20\amos\admin\widgets\icons\WidgetIconAdmin',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['BASIC_USER'],
                ]
            ],

        ];

    }
}
