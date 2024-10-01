<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170719_122922_permissions_community
 */
class m180207_171122_permissions_sondaggi extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'COMPILATORE_SONDAGGI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo compilatore sondaggi',
                'ruleName' => null,
                'parent' => ['BASIC_USER']
            ],
            [
                'name' => open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['COMPILATORE_SONDAGGI']
                ]
            ],
            [
                'name' => 'SONDAGGI_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['COMPILATORE_SONDAGGI']
                ]
            ],
        ];
    }
}
