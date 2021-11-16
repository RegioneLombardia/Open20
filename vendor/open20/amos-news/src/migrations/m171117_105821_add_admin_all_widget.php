<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m171117_105821_add_admin_all_widget
 */
class m171117_105821_add_admin_all_widget extends Migration
{
    const MODULE_NAME = 'news';
    private $widgets;

    private function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\news\widgets\icons\WidgetIconAdminAllNews::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className(),
                'default_order' => 80
            ],

        ];
    }

    public function safeUp()
    {
        $this->initWidgetsConfs();

        foreach ( $this->widgets as $singleWidget )
        {
            $this->insertNewWidget( $singleWidget );
        }

        return true;
    }

    /**
     * Metodo privato per l'inserimento della configurazione per un nuovo widget.
     *
     * @param array $newWidget    Array chiave => valore contenente i dati da inserire nella tabella.
     */
    private function insertNewWidget( $newWidget )
    {
        if ( $this->checkWidgetExist( $newWidget['classname'] ) )
        {
            echo "Widget news ".$newWidget['classname']." esistente. Skippo...\n";
        }
        else
        {
            $this->insert( AmosWidgets::tableName(), $newWidget );
            echo "Widget news ".$newWidget['classname']." creato.\n";
        }
    }

    private function checkWidgetExist( $classname )
    {
        $sql = "SELECT COUNT(classname) FROM ".AmosWidgets::tableName()." WHERE classname LIKE '".addslashes(addslashes($classname))."'";
        $cmd = $this->db->createCommand();
        $cmd->setSql( $sql );
        $oldWidgets = $cmd->queryScalar();
        return ( $oldWidgets > 0 );
    }

    public function safeDown()
    {
        $this->initWidgetsConfs();
        $this->execute("SET foreign_key_checks = 0;");
        foreach ( $this->widgets as $singleWidget )
        {
            $where = " classname LIKE '".addslashes(addslashes($singleWidget['classname']))."'";
            $this->delete( AmosWidgets::tableName(), $where );
        }
        $this->execute("SET foreign_key_checks = 1;");

        return true;
    }
}
