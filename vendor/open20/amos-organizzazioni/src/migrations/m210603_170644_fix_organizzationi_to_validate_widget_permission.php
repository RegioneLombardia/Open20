<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\organizzazioni\rules\WorkflowEnabledRule;
use open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloToValidate;
use yii\rbac\Permission;

/**
 * Class m210603_170644_fix_organizzationi_to_validate_widget_permission
 */
class m210603_170644_fix_organizzationi_to_validate_widget_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => WorkflowEnabledRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Rule to check if the workflow is enabled',
                'ruleName' => WorkflowEnabledRule::className(),
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI'],
                'children' => [WidgetIconProfiloToValidate::className(), 'PROFILO_VALIDATOR']
            ],
            [
                'name' => WidgetIconProfiloToValidate::className(),
                'update' => true,
                'newValues' => [
                    'removeParents' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'PROFILO_VALIDATOR']
                ]
            ]
        ];
    }
}
