<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180123_115982_add_auth_item_event_own_interest
 */
class m180123_115982_add_auth_item_event_own_interest extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventOwnInterest::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEventOwnInterest',
                'ruleName' => null,
                'parent' => ['BASIC_USER', 'EVENTS_ADMINISTRATOR']
            ]
        ];
    }
}
