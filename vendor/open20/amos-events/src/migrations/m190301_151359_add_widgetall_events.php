<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\events\rules\DeleteOwnEventsRule;
use open20\amos\events\rules\UpdateOwnEventsRule;
use yii\rbac\Permission;

/**
 * Class m190301_151359_add_widgetall_events
 */
class m190301_151359_add_widgetall_events extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = array_merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }

    private function setPluginRoles()
    {
        return [
            
        ];
    }

    private function setModelPermissions()
    {
        return [

            
        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconAllEvents::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconAllEvents',
                'ruleName' => null,
                'parent' => [ 'EVENTS_READER']
            ],
            
        ];
    }
}
