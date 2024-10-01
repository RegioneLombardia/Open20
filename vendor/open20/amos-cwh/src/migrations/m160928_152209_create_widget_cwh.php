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
use open20\amos\dashboard\models\AmosWidgets;

class m160928_152209_create_widget_cwh extends AmosMigration
{
    const MODULE_NAME = 'cwh';
    private $widgets;

    public function safeUp()
    {
        $this->initWidgetsConfs();

        foreach ($this->widgets as $singleWidget) {
            $this->insertNewWidget($singleWidget);
        }

        return true;
    }

    private function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\cwh\widgets\icons\WidgetIconCwhAuthAssignment::className(),
                'type' => \open20\amos\dashboard\models\AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ],
            [
                'classname' => \open20\amos\cwh\widgets\icons\WidgetIconCwhRegolePubblicazione::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ],
            [
                'classname' => \open20\amos\cwh\widgets\icons\WidgetIconCwhNodi::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ],
            [
                'classname' => \open20\amos\cwh\widgets\icons\WidgetIconCwhConfig::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ],
//            [
//                'classname' => \open20\amos\news\widgets\icons\WidgetIconNews::className(),
//                'type' => AmosWidgets::TYPE_ICON,
//                'module' => self::MODULE_NAME,
//                'status' => AmosWidgets::STATUS_ENABLED,
//                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className()
//            ],
//            [
//                'classname' => open20\amos\news\widgets\icons\WidgetIconNewsCategorie::className(),
//                'type' => AmosWidgets::TYPE_ICON,
//                'module' => self::MODULE_NAME,
//                'status' => AmosWidgets::STATUS_ENABLED,
//                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className()
//            ],
//            [
//                'classname' => open20\amos\news\widgets\icons\WidgetIconNewsCreatedBy::className(),
//                'type' => AmosWidgets::TYPE_ICON,
//                'module' => self::MODULE_NAME,
//                'status' => AmosWidgets::STATUS_ENABLED,
//                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className()
//            ],
//            [
//                'classname' => open20\amos\news\widgets\icons\WidgetIconNewsDaValidare::className(),
//                'type' => AmosWidgets::TYPE_ICON,
//                'module' => self::MODULE_NAME,
//                'status' => AmosWidgets::STATUS_ENABLED,
//                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className()
//            ],
//            [
//                'classname' => \open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className(),
//                'type' => AmosWidgets::TYPE_GRAPHIC,
//                'module' => self::MODULE_NAME,
//                'status' => AmosWidgets::STATUS_ENABLED,
//                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className()
//            ]
        ];
    }

    /**
     * Metodo privato per l'inserimento della configurazione per un nuovo widget.
     *
     * @param array $newWidget Array chiave => valore contenente i dati da inserire nella tabella.
     */
    private function insertNewWidget($newWidget)
    {
        if ($this->checkWidgetExist($newWidget['classname'])) {
            echo "Widget news " . $newWidget['classname'] . " esistente. Skippo...\n";
        } else {
            $this->insert(AmosWidgets::tableName(), $newWidget);
            echo "Widget news " . $newWidget['classname'] . " creato.\n";
        }
    }

    private function checkWidgetExist($classname)
    {
        $sql = "SELECT COUNT(classname) FROM " . AmosWidgets::tableName() . " WHERE classname LIKE '" . addslashes(addslashes($classname)) . "'";
        $cmd = $this->db->createCommand();
        $cmd->setSql($sql);
        $oldWidgets = $cmd->queryScalar();
        return ($oldWidgets > 0);
    }

    public function safeDown()
    {
        $this->initWidgetsConfs();
        $this->execute("SET foreign_key_checks = 0;");
        foreach ($this->widgets as $singleWidget) {
            $where = " classname LIKE '" . addslashes(addslashes($singleWidget['classname'])) . "'";
            $this->delete(AmosWidgets::tableName(), $where);
        }
        $this->execute("SET foreign_key_checks = 1;");

        return true;
    }
}
