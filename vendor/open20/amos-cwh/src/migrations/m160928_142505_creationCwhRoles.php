<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigration;
use yii\rbac\Permission;

class m160928_142505_creationCwhRoles extends AmosMigration
{
    /**
     * Use this instead of function up().
     */
    public function safeUp()
    {
        return $this->addAuthorizations();
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown()
    {
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
                'name' => 'AMMINISTRATORE_CWH',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo per amministrare CWH',
                'ruleName' => null,
            ],
            [
                'name' => \open20\amos\cwh\widgets\icons\WidgetIconCwhAuthAssignment::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per visualizzare icona WidgetIconCwhAuthAssignment',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => \open20\amos\cwh\widgets\icons\WidgetIconCwhConfig::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per visualizzare icona WidgetIconCwhConfig',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => \open20\amos\cwh\widgets\icons\WidgetIconCwhNodi::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per visualizzare icona WidgetIconCwhNodi',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => \open20\amos\cwh\widgets\icons\WidgetIconCwhRegolePubblicazione::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per visualizzare icona WidgetIconCwhRegolePubblicazione',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWH_PERMISSION_CREATE_open20\amos\discussioni\models\DiscussioniTopic',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Creare open20\\amos\\discussioni\\models\\DiscussioniTopic',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWH_PERMISSION_VALIDATE_open20\amos\discussioni\models\DiscussioniTopic',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Validare open20\\amos\\discussioni\\models\\DiscussioniTopic',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWH_PERMISSION_CREATE_open20\amos\news\models\News',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Creare open20\\amos\\news\\models\\News',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWH_PERMISSION_VALIDATE_open20\amos\news\models\News',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Validare open20\\amos\\news\\models\\News',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_CWH']
            ],

            [
                'name' => 'CWHAUTHASSIGNMENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHAUTHASSIGNMENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHAUTHASSIGNMENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHAUTHASSIGNMENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],

            [
                'name' => 'CWHCONFIG_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHCONFIG_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHCONFIG_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHCONFIG_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],

            [
                'name' => 'CWHNODI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHNODI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHNODI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHNODI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],

            [
                'name' => 'CWHREGOLEPUBBLICAZIONE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHREGOLEPUBBLICAZIONE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHREGOLEPUBBLICAZIONE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],
            [
                'name' => 'CWHREGOLEPUBBLICAZIONE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => '',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_CWH']
            ],

        ];

    }
}
