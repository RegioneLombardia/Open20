<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\rules\FacilitatorsWidgetVisibilityRule;
use open20\amos\admin\widgets\icons\WidgetIconFacilitatorUserProfiles;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m210507_174315_fix_amos_admin_facilitators_widget_permissions
 */
class m210507_174315_fix_amos_admin_facilitators_widget_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => FacilitatorsWidgetVisibilityRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola permesso per vedere la lista dei facilitatori',
                'ruleName' => FacilitatorsWidgetVisibilityRule::className(),
                'parent' => [
                    'ADMIN',
                    'AMMINISTRATORE_UTENTI',
                    'BASIC_USER'
                ],
                'children' => [
                    WidgetIconFacilitatorUserProfiles::className()
                ]
            ],
            [
                'name' => WidgetIconFacilitatorUserProfiles::className(),
                'update' => true,
                'newValues' => [
                    'removeParents' => [
                        'ADMIN',
                        'AMMINISTRATORE_UTENTI',
                        'BASIC_USER'
                    ]
                ]
            ]
        ];
    }
}
