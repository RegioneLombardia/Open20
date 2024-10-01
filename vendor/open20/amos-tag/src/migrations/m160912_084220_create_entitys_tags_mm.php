<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

use yii\db\Schema;

class m160912_084220_create_entitys_tags_mm extends \yii\db\Migration
{

    const TABLE = '{{%entitys_tags_mm}}';

    public function up()
    {

        $this->createTable(self::TABLE, [
            'entitys_tags_mm_id' => Schema::TYPE_PK,
            'classname' => Schema::TYPE_STRING  . '(255) NOT NULL',
            'record_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tag_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'root_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
            'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
            'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
            'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
            'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
            'version' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Versione numero'",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
  
            $this->createIndex('tags_index', self::TABLE, 'classname,record_id,tag_id',false);
        return true;
    }

    public function down()
    {

        $this->dropTable(self::TABLE);
        return true;
    }
}
