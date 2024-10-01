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
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\rules\DeleteFacilitatorOwnDocumentiRule;
use open20\amos\documenti\rules\DeleteOwnDocumentiRule;
use open20\amos\documenti\rules\UpdateFacilitatorOwnDocumentiRule;
use open20\amos\documenti\rules\UpdateOwnDocumentiRule;
use yii\rbac\Permission;

/**
 * Class m161130_104913_add_documenti_all_permissions
 */
class m161130_104913_add_documenti_all_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'AMMINISTRATORE_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore documenti',
                'ruleName' => null,
            ],
            [
                'name' => 'CREATORE_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Creatore documenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI']
            ],
            [
                'name' => 'VALIDATORE_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Validatore documenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI']
            ],
            [
                'name' => 'LETTORE_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Lettore documenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI']
            ],
            [
                'name' => 'AMMINISTRATORE_CATEGORIE_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore categorie documenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI']
            ],
            [
                'name' => 'FACILITATORE_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Facilitatore documenti',
                'ruleName' => null,
            ],

            [
                'name' => 'DOCUMENTICATEGORIE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DocumentiCategorie',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CATEGORIE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTICATEGORIE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model DocumentiCategorie',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CATEGORIE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTICATEGORIE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model DocumentiCategorie',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CATEGORIE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTICATEGORIE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model DocumentiCategorie',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CATEGORIE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTIALLEGATI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DocumentiAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTIALLEGATI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model DocumentiAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTIALLEGATI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model DocumentiAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTIALLEGATI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model DocumentiAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],


            [
                'name' => 'DOCUMENTI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Documenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI', 'CREATORE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Documenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI', 'CREATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI', 'LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Documenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI', DeleteOwnDocumentiRule::className(), DeleteFacilitatorOwnDocumentiRule::className()]
            ],
            [
                'name' => 'DOCUMENTI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Documenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI', UpdateOwnDocumentiRule::className(), UpdateFacilitatorOwnDocumentiRule::className()]
            ],
            [
                'name' => UpdateOwnDocumentiRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di modifica di una proprio documento',
                'ruleName' => UpdateOwnDocumentiRule::className(),
                'parent' => ['CREATORE_DOCUMENTI']
            ],
            [
                'name' => DeleteOwnDocumentiRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di cancellazione di una proprio documento',
                'ruleName' => DeleteOwnDocumentiRule::className(),
                'parent' => ['CREATORE_DOCUMENTI']
            ],
            [
                'name' => UpdateFacilitatorOwnDocumentiRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di modifica di uno documento da parte del facilitatore del creatore',
                'ruleName' => UpdateFacilitatorOwnDocumentiRule::className(),
                'parent' => ['FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => DeleteFacilitatorOwnDocumentiRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di cancellazione di uno documento da parte del facilitatore del creatore',
                'ruleName' => DeleteFacilitatorOwnDocumentiRule::className(),
                'parent' => ['FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow documenti stato bozza',
                'ruleName' => null,
                'parent' => ['CREATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow documenti stato da validare',
                'ruleName' => null,
                'parent' => ['CREATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow documenti stato validato',
                'ruleName' => null,
                'parent' => ['VALIDATORE_DOCUMENTI', 'LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => Documenti::DOCUMENTI_WORKFLOW_STATUS_NONVALIDATO,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow documenti stato non validato',
                'ruleName' => null,
                'parent' => ['VALIDATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconDocumentiDashboard',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => open20\amos\documenti\widgets\icons\WidgetIconDocumenti::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconDocumenti',
                'ruleName' => null,
                'parent' => ['LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetGraphicsUltimiDocumenti',
                'ruleName' => null,
                'parent' => ['LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => open20\amos\documenti\widgets\icons\WidgetIconDocumentiCategorie::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconDocumentiCategorie',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CATEGORIE_DOCUMENTI']
            ],
            [
                'name' => open20\amos\documenti\widgets\icons\WidgetIconDocumentiCreatedBy::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconDocumentiCreatedBy',
                'ruleName' => null,
                'parent' => ['CREATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => open20\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconDocumentiDaValidare',
                'ruleName' => null,
                'parent' => ['VALIDATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => \open20\amos\documenti\widgets\icons\WidgetIconAllDocumenti::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI']
            ],
        ];
    }
}
