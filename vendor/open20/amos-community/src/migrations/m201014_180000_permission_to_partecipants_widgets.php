<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use open20\amos\community\widgets\graphics\WidgetGraphicsCommunityPartecipants;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m201009_180000_permission_to_community_widgets
 */
class m201014_180000_permission_to_partecipants_widgets extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
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
        $prefixStr = 'Permissions for the dashboard of community for the widget ';
        return [
            [
                'name' => WidgetGraphicsCommunityPartecipants::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetGraphicsCommunityPartecipants',
                'parent' => ['BASIC_USER', 'AMMINISTRATORE_COMMUNITY']
            ],
            
        ];
    }
}
