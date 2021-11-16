<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170717_135826_update_admin_widgets_permissions
 */
class m170717_135826_update_admin_widgets_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconMyProfile::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconUserProfile::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconValidatedUserProfiles::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconFacilitatorUserProfiles::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconCommunityManagerUserProfiles::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ]
        ];
    }
}
