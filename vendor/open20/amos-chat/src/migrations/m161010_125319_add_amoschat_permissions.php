<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigration;
use yii\rbac\Permission;

class m161010_125319_add_amoschat_permissions extends AmosMigration
{
    /**
     * Use this instead of function up().
     */
    public function safeUp()
    {
        // If you want to add permissions and roles. If you don't need this delete the code below.
        return $this->addAuthorizations();
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown()
    {
        // If you want to remove permissions and roles. If you don't need this delete the code below.
        return $this->removeAuthorizations();
    }

    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => \open20\amos\chat\widgets\icons\WidgetIconChat::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per vedere il widget di accesso ai messaggi',
                'ruleName' => null
            ]
        ];
    }
}
