<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m220615_144800_add_permissions_to_basic_user_and_staff_een_roles extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = array_merge(
            $this->setRolesRoles(),
            $this->setModelPermissions()
        );
    }

    private function setRolesRoles()
    {
        return [
            [
                'name' => 'COLLABORATIONS_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Collaborations plugin reader',
                'ruleName' => null,
                'parent' => ['BASIC_USER']
            ],
            /*[
                'name' => 'COLLABORATIONS_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Collaborations plugin Administrator',
                'ruleName' => null,
                'parent' => ['STAFF_EEN']
            ],*/
        ];
    }

    private function setModelPermissions()
    {
        return [

        ];
    }
}
