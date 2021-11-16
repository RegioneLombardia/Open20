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
 * Class m170303_110707_init_widget_to_validate_communities_permissions
 */
class m170303_110707_init_widget_to_validate_communities_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'open20\amos\community\widgets\icons\WidgetIconToValidateCommunities',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Dashboard permission for widget ' . 'WidgetIconToValidateCommunities',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY', 'COMMUNITY_VALIDATE']
            ],
        ];
    }
}
