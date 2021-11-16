<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m161010_124916_create_widgets_chat
 */
class m161010_124916_create_widgets_chat extends Migration
{
    const MODULE_NAME = 'chat';
    private $widgets;

    private function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\chat\widgets\icons\WidgetIconChat::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ]
        ];
    }

    /**
     * @return bool
     */
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
            echo "Widget ".self::MODULE_NAME." ".$newWidget['classname']." esistente. Skippo...\n";
        }
        else
        {
            $this->insert( AmosWidgets::tableName(), $newWidget );
            echo "Widget ".self::MODULE_NAME." ".$newWidget['classname']." creato.\n";
        }
    }

    /**
     * @param $classname
     * @return bool
     */
    private function checkWidgetExist( $classname )
    {
        /** @var \yii\db\ActiveQuery $query */
        $query = AmosWidgets::find()->andWhere(['classname' => $classname]);
        $oldWidgets = $query->count("classname");
        return ( $oldWidgets > 0 );
    }

    /**
     * @return bool
     */
    public function safeDown()
    {
        $this->initWidgetsConfs();
        $this->execute("SET foreign_key_checks = 0;");
        foreach ( $this->widgets as $singleWidget )
        {
            $where = ['classname' => $singleWidget['classname'] ];
            $this->delete( AmosWidgets::tableName(), $where );
        }
        $this->execute("SET foreign_key_checks = 1;");
        
        return true;
    }
}