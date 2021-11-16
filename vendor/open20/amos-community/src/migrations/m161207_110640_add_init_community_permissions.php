<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m161207_110640_add_init_community_permissions
 */
class m161207_110640_add_init_community_permissions extends AmosMigrationPermissions
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
            [
                'name' => 'AMMINISTRATORE_COMMUNITY',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo amministratore per il plugin community',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ]
        ];
    }

    private function setModelPermissions()
    {
        return [

            // Permessi per il model Community
            [
                'name' => 'COMMUNITY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di creazione per il model Community',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'COMMUNITY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di lettura per il model Community',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'COMMUNITY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica per il model Community',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'COMMUNITY_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di cancellazione per il model Community',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],

            // Permessi per il model TipologiaCommunity
            [
                'name' => 'TIPOLOGIACOMMUNITY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di creazione per il model TipologiaCommunity',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'TIPOLOGIACOMMUNITY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di lettura per il model TipologiaCommunity',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'TIPOLOGIACOMMUNITY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica per il model TipologiaCommunity',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'TIPOLOGIACOMMUNITY_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di cancellazione per il model TipologiaCommunity',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],

            // Permessi per il model CommunityTipologiaCommunityMm
            [
                'name' => 'COMMUNITYTIPOLOGIACOMMUNITYMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di creazione per il model CommunityTipologiaCommunityMm',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'COMMUNITYTIPOLOGIACOMMUNITYMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di lettura per il model CommunityTipologiaCommunityMm',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'COMMUNITYTIPOLOGIACOMMUNITYMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica per il model CommunityTipologiaCommunityMm',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'COMMUNITYTIPOLOGIACOMMUNITYMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di cancellazione per il model CommunityTipologiaCommunityMm',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permesso per la dashboard per il widget ';
        return [
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconCommunityDashboard',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => 'open20\amos\community\widgets\icons\WidgetIconTipologiaCommunity',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconTipologiaCommunity',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconCommunity::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconCommunity',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconMyCommunities::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconMyCommunities',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconCreatedByCommunities::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconCreatedByCommunities',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
        ];
    }
}
