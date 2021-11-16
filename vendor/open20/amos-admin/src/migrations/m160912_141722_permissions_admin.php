<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;
use mdm\admin\models\AuthItem;
use open20\amos\admin\rbac\UpdateOwnUserProfile;

class m160912_141722_permissions_admin extends Migration
{

    private $perms;
    private $tabella = null;

    public function __construct()
    {
        $this->tabella = '{{%auth_item}}';
        parent::__construct();
    }

    private function setPermissionConfs()
    {
        $this->perms = array(
            array(
                'name' => 'ADMIN',
                'type' => '1',
                'description' => 'Amministratore del Sistema'
            ),
            array(
                'name' => 'GESTIONE_UTENTI',
                'type' => '2',
                'description' => 'Permessi avanzati sulla gestione utenti'
            ),
            array(
                'name' => 'USERPROFILE_READ',
                'type' => '2',
                'description' => 'Permesso di READ sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILE_CREATE',
                'type' => '2',
                'description' => 'Permesso di CREATE sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILE_DELETE',
                'type' => '2',
                'description' => 'Permesso di DELETE sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILE_UPDATE',
                'type' => '2',
                'description' => 'Permesso di UPDATE sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILESTATICIVILI_READ',
                'type' => '2',
                'description' => 'Permesso di READ sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILESTATICIVILI_CREATE',
                'type' => '2',
                'description' => 'Permesso di CREATE sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILESTATICIVILI_DELETE',
                'type' => '2',
                'description' => 'Permesso di DELETE sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILESTATICIVILI_UPDATE',
                'type' => '2',
                'description' => 'Permesso di UPDATE sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILETITOLISTUDIO_READ',
                'type' => '2',
                'description' => 'Permesso di READ sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILETITOLISTUDIO_CREATE',
                'type' => '2',
                'description' => 'Permesso di CREATE sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILETITOLISTUDIO_DELETE',
                'type' => '2',
                'description' => 'Permesso di DELETE sul model UserProfile',
            ),
            array(
                'name' => 'USERPROFILETITOLISTUDIO_UPDATE',
                'type' => '2',
                'description' => 'Permesso di UPDATE sul model UserProfile',
            ),
            array(
                'name' => \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                'type' => '2',
                'description' => 'Permesso di visualizzazione del widget Il mio profilo (grafico)',
            ),
            array(
                'name' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'type' => '2',
                'description' => 'Permesso di visualizzazione del widget Amministrazione',
            ),
            array(
                'name' => \open20\amos\admin\widgets\icons\WidgetIconMyProfile::className(),
                'type' => '2',
                'description' => 'Permesso di visualizzazione del widget Il mio profilo',
            ),
            array(
                'name' => \open20\amos\admin\widgets\icons\WidgetIconUserProfile::className(),
                'type' => '2',
                'description' => 'Permesso di visualizzazione del widget Gestione utenti',
            )
        );
    }

    public function safeUp()
    {
        $this->setPermissionConfs();

        $ruleTmp = new UpdateOwnUserProfile();
        $rule = \Yii::$app->authManager->getRule($ruleTmp->name);
        if (is_null($rule)) {
            $rule = new UpdateOwnUserProfile();
            $ok = \Yii::$app->authManager->add($rule);
            if (!$ok) {
                return false;
            }
        }

        $this->insert('{{%auth_item}}', [
            'name' => 'UpdateOwnUserProfile',
            'type' => '2',
            'description' => 'Permesso di aggiornare il proprio profilo',
            'rule_name' => 'isYourProfile'
        ]);

        foreach ($this->perms as $singlePerm) {
            $cmd = $this->db->createCommand();
            $cmd->setSql("SELECT name FROM auth_item WHERE name LIKE '" . $singlePerm['name'] . "'");
            $authItems = $cmd->queryColumn();

            if (empty($authItems)) {
                $this->createNewPermission($singlePerm['name'], $singlePerm['type'], $singlePerm['description']);
                echo "Nuova permission " . $singlePerm['name'] . " creata.\n";
            } else {
                echo "Permission " . $singlePerm['name'] . " esistente. Skippo...\n";
            }
        }

        $this->batchInsert('{{%auth_assignment}}',['item_name', 'user_id'], [
            ['ADMIN', '1'],
        ]);

        $this->batchInsert('{{%auth_item_child}}',['parent', 'child'], [
            ['ADMIN', \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className()],
            ['ADMIN', \open20\amos\admin\widgets\icons\WidgetIconAdmin::className()],
            ['ADMIN', \open20\amos\admin\widgets\icons\WidgetIconMyProfile::className()],
            ['ADMIN', \open20\amos\admin\widgets\icons\WidgetIconUserProfile::className()],
            ['ADMIN', 'GESTIONE_UTENTI'],
            ['ADMIN', 'USERPROFILE_READ'],
            ['ADMIN', 'USERPROFILE_CREATE'],
            ['ADMIN', 'USERPROFILE_DELETE'],
            ['ADMIN', 'USERPROFILE_UPDATE'],
            ['ADMIN', 'USERPROFILESTATICIVILI_READ'],
            ['ADMIN', 'USERPROFILESTATICIVILI_CREATE'],
            ['ADMIN', 'USERPROFILESTATICIVILI_DELETE'],
            ['ADMIN', 'USERPROFILESTATICIVILI_UPDATE'],
            ['ADMIN', 'USERPROFILETITOLISTUDIO_READ'],
            ['ADMIN', 'USERPROFILETITOLISTUDIO_CREATE'],
            ['ADMIN', 'USERPROFILETITOLISTUDIO_DELETE'],
            ['ADMIN', 'USERPROFILETITOLISTUDIO_UPDATE'],
            ['UpdateOwnUserProfile', 'USERPROFILE_UPDATE'],
        ]);

        $now = date("Y-m-d H:i:s");
        $module = 'admin';
        $status = 1;
        $userId = 1;
        $this->batchInsert('{{%amos_widgets}}',['classname', 'type', 'module', 'status', 'child_of', 'created_by', 'created_at', 'updated_by', 'updated_at'], [
            [
                \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                'GRAPHIC',
                $module,
                $status,
                \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                $userId,
                $now,
                $userId,
                $now
            ],
            [
                \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'ICON',
                $module,
                $status,
                null,
                $userId,
                $now,
                $userId,
                $now
            ],
            [
                \open20\amos\admin\widgets\icons\WidgetIconMyProfile::className(),
                'ICON',
                $module,
                $status,
                \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                $userId,
                $now,
                $userId,
                $now
            ],
            [
                \open20\amos\admin\widgets\icons\WidgetIconUserProfile::className(),
                'ICON',
                $module,
                $status,
                \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                $userId,
                $now,
                $userId,
                $now
            ]
        ]);
    }

    /**
     * Metodo privato per la creazione della singola permission nella tabella auth_item
     *
     * @param string $name          Nome univoco della permission
     * @param string $type          Tipo della permission (0, 1, 2)
     * @param string $description   Descrizione della permission
     */
    private function createNewPermission($name, $type, $description)
    {
        $this->insert($this->tabella, [
            'name' => $name,
            'type' => $type,
            'description' => $description
        ]);
    }

    public function safeDown()
    {
        $this->setPermissionConfs();

        foreach ($this->perms as $singlePerm) {
            $cmd = $this->db->createCommand();
            $cmd->setSql("SELECT name FROM auth_item WHERE name LIKE '" . addslashes(addslashes($singlePerm['name'])) . "'");
            $authItems = $cmd->queryColumn();

            if (!empty($authItems)) {
                $this->deletePermission($singlePerm['name']);
                echo "Permission " . $singlePerm['name'] . " eliminata.\n";
            } else {
                echo "Permission " . addslashes(addslashes($singlePerm['name'])) . " non trovata. Skippo...\n";
            }
        }
    }

    /**
     * Metodo privato per l'eliminazione di una permission dalla tabella auth_item
     *
     * @param string $name          Nome univoco della permission
     */
    private function deletePermission($name)
    {
        $this->delete($this->tabella, [
            'name' => $name
        ]);
    }
}
