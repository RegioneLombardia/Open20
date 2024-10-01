<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\organizzazioni\models\Profilo;

class m171218_163452_rename_organizzazioni_denominazione extends Migration
{
    public function safeUp()
    {

        try{
            $this->renameColumn(Profilo::tableName(), 'denominazione', 'name');
        } catch (Exception $ex) {
            yii\helpers\Console::output($ex->getMessage());
        }
        return true;
    }

    public function safeDown()
    {
        
        try{
            $this->renameColumn(Profilo::tableName(), 'name', 'denominazione');
        } catch (Exception $ex) {
            yii\helpers\Console::output($ex->getMessage());
        }
        return true;
    }

   
}
