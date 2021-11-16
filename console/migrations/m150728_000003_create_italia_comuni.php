<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use yii\db\Schema;

class m150728_000003_create_italia_comuni extends \yii\db\Migration
{/*
    const TABLE = '{{%italia_comuni}}';

    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
   
        $this->createTable(self::TABLE, [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_TEXT . " NOT NULL",
            'codice_catastale' => Schema::TYPE_STRING . '(5) NOT NULL',
            'regione_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'provincia_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);               
        }
        else{
            echo "Nessuna creazione eseguita in quanto la tabella esiste già";
            return true;
        }
 
    }

    public function down()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {            
            $this->dropTable(self::TABLE);
        }
        else{
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
            return true;
        }
    }
*/
}