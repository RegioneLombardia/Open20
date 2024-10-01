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
 * Class m171008_090411_add_documenti_cwh_permission
 */
class m171008_090411_add_documenti_cwh_permission extends AmosMigrationPermissions
{

    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [

            [
                'name' => 'CWH_PERMISSION_CREATE_open20\amos\documenti\models\Documenti',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Creare open20\\amos\\documenti\\models\\Docuemnti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWH_PERMISSION_VALIDATE_open20\amos\documenti\models\Documenti',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Validare open20\\amos\\documenti\\models\\Documenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],
        ];
    }
}
