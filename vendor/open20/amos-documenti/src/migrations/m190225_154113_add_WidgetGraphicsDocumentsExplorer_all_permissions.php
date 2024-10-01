<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\documenti\rules\DeleteOwnDocumentiRule;
use open20\amos\documenti\rules\DeleteFacilitatorOwnDocumentiRule;
use open20\amos\documenti\rules\UpdateFacilitatorOwnDocumentiRule;
use open20\amos\documenti\rules\UpdateOwnDocumentiRule;
use open20\amos\documenti\models\Documenti;
use yii\rbac\Permission;

class m190225_154113_add_WidgetGraphicsDocumentsExplorer_all_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsExplorer::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetGraphicsDocumentsExplorer',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
        ];
    }
}
