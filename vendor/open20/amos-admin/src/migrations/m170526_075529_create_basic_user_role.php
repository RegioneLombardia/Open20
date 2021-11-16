<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\rules\ValidatedBasicUserRule;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170526_075529_create_basic_user_role
 */
class m170526_075529_create_basic_user_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'BASIC_USER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Basic user role for all platform users',
            ],
            [
                'name' => 'VALIDATED_BASIC_USER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Validated basic user role',
                'ruleName' => ValidatedBasicUserRule::className(),
                'parent' => ['BASIC_USER']
            ],
            [
                'name' => open20\amos\admin\widgets\icons\WidgetIconMyProfile::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di visualizzazione del widget Il mio profilo.',
                'ruleName' => null,
                'parent' => ['BASIC_USER'],
            ],
            [
                'name' => open20\amos\admin\widgets\icons\WidgetIconUserProfile::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di visualizzazione del widget dei profili.',
                'ruleName' => null,
                'parent' => ['BASIC_USER'],
            ],
            [
                'name' =>open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di visualizzazione del widget dei profili.',
                'ruleName' => null,
                'parent' => ['BASIC_USER'],
            ],
            [
                'name' => 'UpdateOwnUserProfile',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica Il mio profilo.',
                'ruleName' => null,
                'parent' => ['BASIC_USER'],
            ],
            [
                'name' => 'USERPROFILE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica del profilo.',
                'ruleName' => null,
                'parent' => ['BASIC_USER'],
            ],

        ];
    }
}
