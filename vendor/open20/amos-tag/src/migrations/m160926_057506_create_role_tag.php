<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

use yii\db\Migration;

use open20\amos\core\migration\AmosMigration;
use yii\rbac\Permission;

class m160926_057506_create_role_tag extends AmosMigration
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
            $this->setTagPluginRoles(),
            $this->setTagModelPermissions(),
            $this->setTagWidgetsPermissions()
        );
    }

    /**
     * Tag plugin roles.
     *
     * @return array
     */
    private function setTagPluginRoles()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_TAG',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore tag',
                'ruleName' => null,
            ],

        ];
    }

    /**
     * Tag model permissions
     *
     * @return array
     */
    private function setTagModelPermissions()
    {
        return [
            [
                'name' => 'TAG_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Tag',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_TAG']
            ],
            [
                'name' => 'TAG_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Tag',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_TAG']
            ],
            [
                'name' => 'TAG_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Tag',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_TAG']
            ],
            [
                'name' => 'TAG_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Tag',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_TAG']
            ],
        ];
    }

    /**
     * News plugin widgets permissions
     *
     * @return array
     */
    private function setTagWidgetsPermissions()
    {
        return [
            [
                'name' =>\open20\amos\tag\widgets\icons\WidgetIconTag::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTag',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_TAG']
            ],
            [
                'name' =>\open20\amos\tag\widgets\icons\WidgetIconTagManager::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTagManager',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_TAG']
            ],
        ];
    }


}
