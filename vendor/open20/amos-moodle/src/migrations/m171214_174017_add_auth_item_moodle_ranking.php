<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m171214_174017_add_auth_item_moodle_ranking
 */
class m171214_174017_add_auth_item_moodle_ranking extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleRanking::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconMoodleRanking',
                'ruleName' => null,
                'parent' => ['ADMIN', 'MOODLE_ADMIN', 'MOODLE_STUDENT']
            ]
        ];
    }
}
