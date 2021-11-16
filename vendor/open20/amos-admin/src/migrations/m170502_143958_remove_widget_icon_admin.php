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

class m170502_143958_remove_widget_icon_admin extends Migration
{
    private $tabella = null;


    public function __construct()
    {
        $this->tabella = '{{%auth_item}}';
        parent::__construct();
    }

    public function safeUp()
    {
        $this->delete(
            '{{%amos_user_dashboards_widget_mm}}',
            [
                'amos_widgets_classname' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className()
            ]
        );
        $this->delete(
            '{{%auth_item_child}}',
            [
                'child' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className()
            ]
        );

        $this->delete(
            '{{%auth_item}}',
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className()
            ]
        );

        $this->update(
            '{{%amos_widgets}}',
            [
                'child_of' => null
            ],
            [
                'classname' => \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className()
            ]
        );
        $this->update(
            '{{%amos_widgets}}',
            [
                'child_of' => null
            ],
            [
                'classname' =>  \open20\amos\admin\widgets\icons\WidgetIconMyProfile::className()
            ]
        );
        $this->update(
            '{{%amos_widgets}}',
            [
                'child_of' => null
            ],
            [
                'classname' =>  \open20\amos\admin\widgets\icons\WidgetIconUserProfile::className()
            ]
        );
        $this->delete(
            '{{%amos_widgets}}',
            [
                'classname' =>  \open20\amos\admin\widgets\icons\WidgetIconAdmin::className()
            ]
        );
    }

    public function safeDown()
    {
        $singlePerm = array(
            'name' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
            'type' => '2',
            'description' => 'Permesso di visualizzazione del widget Amministrazione',
        );
        $cmd = $this->db->createCommand();
        $cmd->setSql("SELECT name FROM auth_item WHERE name LIKE '" . $singlePerm['name'] . "'");
        $authItems = $cmd->queryColumn();

        if (empty($authItems)) {
            $this->createNewPermission($singlePerm['name'], $singlePerm['type'], $singlePerm['description']);
            echo "Nuova permission " . $singlePerm['name'] . " creata.\n";
        } else {
            echo "Permission " . $singlePerm['name'] . " esistente. Skippo...\n";
        }
        $this->batchInsert('{{%auth_item_child}}',['parent', 'child'], [
            [
                'ADMIN', \open20\amos\admin\widgets\icons\WidgetIconAdmin::className()
            ]
        ]);


        $now = date("Y-m-d H:i:s");
        $module = 'admin';
        $status = 1;
        $userId = 1;
        $this->batchInsert('{{%amos_widgets}}',['classname', 'type', 'module', 'status', 'child_of', 'created_by', 'created_at', 'updated_by', 'updated_at'], [
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
        ]);

        $this->update(
            '{{%amos_widgets}}',
            [
                'child_of' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className()
            ],
            [
                'classname' =>  \open20\amos\admin\widgets\icons\WidgetIconMyProfile::className()
            ]
        );

        $this->update(
            '{{%amos_widgets}}',
            [
                'child_of' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className()
            ],
            [
                'classname' =>  \open20\amos\admin\widgets\icons\WidgetIconUserProfile::className()
            ]
        );
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
}
