<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */


use yii\db\Migration;
use yii\db\Query;

/**
 * Class m210326_095400_update_fields_color_category
 */
class m230811_100000_add_defaut_documenti_category extends Migration
{



    /**
     * se checkNumberCategory restituisce true allora inserisco la categoria generica
     * @inheritdoc
     */
    public function safeUp()
    {
        if($this->checkNumberCategory()){
            $this->insert('documenti_categorie', [
                'titolo' => 'Default',
                'descrizione_breve' => 'categoria creata in automatico alla prima installazione',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
    /**
     * check se la tabella contiene categorie attive
     * @return bool
     */

    private function checkNumberCategory(){
        
        $sql = 'SELECT COUNT(id) FROM documenti_categorie WHERE deleted_at is null';
        
        $cmd = $this->db->createCommand();
        $cmd->setSql( $sql );
        $newsCategory = $cmd->queryScalar();
        return ( $newsCategory == 0 );
    }
}
