<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170412_085218_add_permission_document_validate
 */
class m170412_085218_add_permission_document_validate extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DocumentValidate',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a document with cwh query',
                'ruleName' => \open20\amos\core\rules\ValidatorUpdateContentRule::className(),
                'parent' => ['VALIDATORE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTI_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['DocumentValidate'],
                    'removeParents' => ['VALIDATORE_DOCUMENTI']
                ]
            ]
        ];
    }
}
