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

class m160912_084200_create_tag extends \yii\db\Migration
{

    const TABLE = '{{%tag}}';

    public function up()
    {

        $this->createTable(self::TABLE, [
            'id' => Schema::TYPE_PK,
            'root' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'lvl' => 'SMALLINT(5) NOT NULL',
            'nome' => Schema::TYPE_STRING . '(60) NOT NULL',
            'codice' => Schema::TYPE_STRING . '(60) DEFAULT NULL',
            'descrizione' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'icon' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'icon_type' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'active' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'selected' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'disabled' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'readonly' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'visible' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'collapsed' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'movable_u' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'movable_d' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'movable_l' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'movable_r' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'removable' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'removable_all' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'frequency' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
            'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
            'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
            'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
            'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
            'version' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Versione numero'",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        return true;
    }

    public function down()
    {

        $this->dropTable(self::TABLE);
        return true;
    }
}
