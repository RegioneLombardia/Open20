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
class m200124_101325_subscribe_user_to_fad_widget_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\moodle\widgets\SubscribeUserToFADCourseWidget::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso SubscribeUserToFADCourseWidget',
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_READER']
            ],
        ];
    }
}
