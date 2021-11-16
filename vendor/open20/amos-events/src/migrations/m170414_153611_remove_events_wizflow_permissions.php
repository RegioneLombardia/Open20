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

class m170414_153611_remove_events_wizflow_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'EventCreationWizardWorkflow/INTRODUCTION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Wizflow step permission: introduction',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
            ],
            [
                'name' => 'EventCreationWizardWorkflow/DESCRIPTION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Wizflow step permission: description',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
            ],
            [
                'name' => 'EventCreationWizardWorkflow/ORGANIZATIONALDATA',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Wizflow step permission: organizational data',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
            ],
            [
                'name' => 'EventCreationWizardWorkflow/PUBLICATION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Wizflow step permission: publication',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
            ],
            [
                'name' => 'EventCreationWizardWorkflow/SUMMARY',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Wizflow step permission: summary',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
            ]
        ];
    }
}
