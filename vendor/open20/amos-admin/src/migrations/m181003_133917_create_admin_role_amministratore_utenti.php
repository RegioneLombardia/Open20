<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m181003_133917_create_admin_role_amministratore_utenti
 */
class m181003_133917_create_admin_role_amministratore_utenti extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_UTENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for users',
                'parent' => ['ADMIN'],
                'children' => [
                    'GESTIONE_UTENTI',
                    'CHANGE_USERPROFILE_WORKFLOW_STATUS',
                    'DeactivateAccount',
                    'USERPROFILE_CREATE',
                    'USERPROFILE_READ',
                    'USERPROFILE_UPDATE',
                    'USERPROFILE_DELETE',
                    UserProfile::USERPROFILE_WORKFLOW_STATUS_DRAFT,
                    UserProfile::USERPROFILE_WORKFLOW_STATUS_TOVALIDATE,
                    UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED,
                    UserProfile::USERPROFILE_WORKFLOW_STATUS_NOTVALIDATED,
                    \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                    \open20\amos\admin\widgets\icons\WidgetIconMyProfile::className(),
                    \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                    \open20\amos\admin\widgets\icons\WidgetIconUserProfile::className(),
                    \open20\amos\admin\widgets\icons\WidgetIconValidatedUserProfiles::className(),
                    \open20\amos\admin\widgets\icons\WidgetIconFacilitatorUserProfiles::className(),
                    \open20\amos\admin\widgets\icons\WidgetIconCommunityManagerUserProfiles::className(),
                    \open20\amos\admin\widgets\icons\WidgetIconInactiveUserProfiles::className()
                ]
            ]
        ];
    }
}
