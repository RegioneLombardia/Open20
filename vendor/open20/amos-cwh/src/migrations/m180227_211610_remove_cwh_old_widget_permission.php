<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\dashboard\models\AmosWidgets;

class m180227_211610_remove_cwh_old_widget_permission extends AmosMigrationPermissions
{
    const MODULE_NAME = 'cwh';
    private $widgets;
    
    /**
     * 
     */
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
        ];
    }

    /**
     * 
     */
    private function insertWidgets()
    {
        $this->initWidgetsConfs();
        foreach ($this->widgets as $singleWidget) {
            $this->insertNewWidget($singleWidget);
        }
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

    private function removeWidgets()
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
    
    /**
     * 
     */
    public function init() {
        $this->setProcessInverted(true);
        parent::init();
    }


    /**
     * 
     */
    protected function setAuthorizations()
    { 

        $this->authorizations = [
            
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
        ];

    }
    
    /**
     * 
     * @return boolean
     */
    public function safeUp() {
        try{
            parent::safeUp();
            $this->removeWidgets();
            $ret = true;
        }catch(\Exception $ex){
            \yii\helpers\Console::stdout($ex->getMessage());
            
        }
        return $ret;
    }
    
    /**
     * 
     * @return boolean
     */
    public function safeDown() {
        $ret = false;
        try{
            $this->insertWidgets();
            parent::safeDown();
            $ret = true;
        }catch(\Exception $ex){
            \yii\helpers\Console::stdout($ex->getMessage());
        }
        
        return $ret;
    }
    
}
