<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use amos\chrono\widgets\icons\WidgetIconChronoTasks;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m200410_105723_chrono_permissions_widgets extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => WidgetIconChronoTasks::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Chrono',
                'parent' => ['AMMINISTRATORE_CHRONOTASK']
            ],
        ];
    }
}