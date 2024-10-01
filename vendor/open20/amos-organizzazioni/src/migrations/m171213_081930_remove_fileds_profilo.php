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
use yii\db\Schema;

class m171213_081930_remove_fileds_profilo extends Migration
{

    const TABLE_PROFILO = '{{%profilo}}';

    public function safeUp()
    {
        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_PROFILO);
        if(isset($table->columns['logo'])) {
            $this->dropColumn(self::TABLE_PROFILO, 'logo');
        }
        if(isset($table->columns['allegati'])) {
            $this->dropColumn(self::TABLE_PROFILO, 'allegati');
        }
        if(isset($table->columns['ambiti_tecnologici_su_cui_siet'])) {
            $this->dropColumn(self::TABLE_PROFILO, 'ambiti_tecnologici_su_cui_siet');
        }
        if(isset($table->columns['principali_ambiti_di_attivita_organizzazione'])) {
            $this->dropColumn(self::TABLE_PROFILO, 'principali_ambiti_di_attivita_organizzazione');
        }

        return true;
    }

    public function safeDown()
    {
        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_PROFILO);
        if(!isset($table->columns['logo'])) {
            $this->addColumn(self::TABLE_PROFILO, 'logo',Schema::TYPE_STRING . "(255) NULL COMMENT 'Logo'");
        }
        if(!isset($table->columns['allegati'])) {
            $this->addColumn(self::TABLE_PROFILO, 'allegati',Schema::TYPE_STRING . "(255) NULL COMMENT 'Allegati'");
        }
        if(!isset($table->columns['ambiti_tecnologici_su_cui_siet'])) {
            $this->addColumn(self::TABLE_PROFILO, 'ambiti_tecnologici_su_cui_siet',Schema::TYPE_STRING . "(255) NULL COMMENT 'Ambiti tecnologici su cui siete interessati a'");
        }
        if(!isset($table->columns['principali_ambiti_di_attivita_organizzazione'])) {
            $this->addColumn(self::TABLE_PROFILO, 'principali_ambiti_di_attivita_organizzazione',Schema::TYPE_STRING . "(255) NULL COMMENT 'Principali ambiti di attività in cui opera la'");
        }

        return true;
    }


}
