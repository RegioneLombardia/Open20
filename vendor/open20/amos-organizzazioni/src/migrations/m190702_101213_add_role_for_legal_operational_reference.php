<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    care-for-workers\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\rules\UpdateLegalOperationalReferenceRule;


use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190410_150048_add_care_for_workers_roles
 */
class m190702_101213_add_role_for_legal_operational_reference extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'MANAGER_ORGANIZZAZIONE',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo del responsabile di una organizzazione',
                'children' => [
                    'BASIC_USER',
                    'PROFILO_UPDATE',
                    'LETTORE_ORGANIZZAZIONI',
                    UpdateLegalOperationalReferenceRule::className(),
                ]
            ],
            [
                'name' => UpdateLegalOperationalReferenceRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per ruolo responsabile per le organizzazioni per cui è rappresentante legale o referente operativo',
                'ruleName' => UpdateLegalOperationalReferenceRule::className(),
                'children' => [
                    'PROFILO_UPDATE',
                ]
            ],
        ];
    }
}
