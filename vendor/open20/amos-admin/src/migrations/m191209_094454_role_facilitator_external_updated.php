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
use yii\rbac\Permission;

/**
 * Class m181018_134854_add_admin_tag_tabs_permission
 */
class m191209_094454_role_facilitator_external_updated extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'VALIDATOR',
                'type' => Permission::TYPE_ROLE,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['FACILITATOR_EXTERNAL']
                ]
            ],
//            [
//                'name' => 'FACILITATORE_NEWS',
//                'type' => Permission::TYPE_ROLE,
//                'update' => true,
//                'newValues' => [
//                    'addParents' => ['FACILITATOR_EXTERNAL']
//                ]
//            ],
//            [
//                'name' => 'FACILITATORE_DISCUSSIONI',
//                'type' => Permission::TYPE_ROLE,
//                'update' => true,
//                'newValues' => [
//                    'addParents' => ['FACILITATOR_EXTERNAL']
//                ]
//            ],
//            [
//                'name' => 'FACILITATORE_DOCUMENTI',
//                'type' => Permission::TYPE_ROLE,
//                'update' => true,
//                'newValues' => [
//                    'addParents' => ['FACILITATOR_EXTERNAL']
//                ]
//            ],
//            [
//                'name' => 'SHOWCASEPROJECT_FACILITATOR',
//                'type' => Permission::TYPE_ROLE,
//                'update' => true,
//                'newValues' => [
//                    'addParents' => ['FACILITATOR_EXTERNAL']
//                ]
//            ],
        ];
    }
}
