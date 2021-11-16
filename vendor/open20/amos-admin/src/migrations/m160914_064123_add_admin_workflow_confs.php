<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\migration\AmosMigration;
use yii\rbac\Permission;

class m160914_064123_add_admin_workflow_confs extends AmosMigration
{
    /**
     * Use this instead of function up().
     */
    public function safeUp()
    {
        $ok = $this->addAuthorizations();
        return $ok;
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown()
    {
        $ok = $this->removeAuthorizations();
        return $ok;
    }

    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = array_merge(
//            $this->setPluginRoles(),
            $this->setWorkflowStatusPermissions()
        );
    }

    /**
     * News plugin roles.
     *
     * @return array
     */
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'CREATORE_UTENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Creatore utenti',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'VALIDATORE_UTENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Validatore utenti',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }

    /**
     * News workflow statuses permissions
     *
     * @return array
     */
    private function setWorkflowStatusPermissions()
    {
        return [
            [
                'name' => 'UserProfileWorkflow/ATTIVO',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow admin stato attivo',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'UserProfileWorkflow/ATTIVOEVALIDATO',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow admin stato attivo e validato',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'UserProfileWorkflow/ATTIVOCONRICHIESTAVALIDAZIONE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow admin stato attivo con richiesta validazione',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'UserProfileWorkflow/ATTIVONONVALIDATO',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow admin stato attivo non validato',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'UserProfileWorkflow/DISATTIVO',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow admin stato disattivo',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'UserProfileWorkflow/MODIFICAINCORSO',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow admin stato modifica in corso',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}
