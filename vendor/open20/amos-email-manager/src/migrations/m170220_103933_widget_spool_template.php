<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use open20\amos\emailmanager\widgets\icons\WidgetIconSpool;
use open20\amos\emailmanager\widgets\icons\WidgetIconTemplate;
use yii\db\Migration;

class m170220_103933_widget_spool_template extends Migration
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
                'name' => WidgetIconTemplate::className(),
                'type' => '2',
                'description' => 'Permesso di visualizzazione del widget Gestione email',
            ),
            array(
                'name' => WidgetIconSpool::className(),
                'type' => '2',
                'description' => 'Permesso di visualizzazione del widget Gestione email',
            )
        );
    }

    public function safeUp()
    {
        $this->setPermissionConfs();

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


        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            ['ADMIN', WidgetIconTemplate::className()],
            ['ADMIN', WidgetIconSpool::className()],
        ]);

        $now = date("Y-m-d H:i:s");
        $module = 'email';
        $status = 1;
        $userId = 1;
        $this->batchInsert('{{%amos_widgets}}', ['classname', 'type', 'module', 'status', 'child_of', 'created_by', 'created_at', 'updated_by', 'updated_at'], [
            [
                WidgetIconTemplate::className(),
                'ICON',
                $module,
                $status,
                'open20\amos\emailmanager\widgets\icons\WidgetIconEmailManager',
                $userId,
                $now,
                $userId,
                $now
            ],
            [
                WidgetIconSpool::className(),
                'ICON',
                $module,
                $status,
                'open20\amos\emailmanager\widgets\icons\WidgetIconEmailManager',
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
     * @param string $name Nome univoco della permission
     * @param string $type Tipo della permission (0, 1, 2)
     * @param string $description Descrizione della permission
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
     * @param string $name Nome univoco della permission
     */
    private function deletePermission($name)
    {
        $this->delete($this->tabella, [
            'name' => $name
        ]);
    }
}
