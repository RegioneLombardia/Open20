<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

class m160929_141930_cwh_tag_owner_interest_mm extends Migration
{
    const TABLE = "cwh_tag_owner_interest_mm";

    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'interest_classname' => $this->string(255)->null()->comment('Contenuto di preferenza'),
            'classname' => $this->string(255)->null()->comment('Proprietario'),
            'record_id' => $this->string(255)->null()->comment('Proprietario id'),
            'tag_id' => $this->integer(11)->null()->comment('Tag'),
            'root_id' => $this->integer(11)->null()->comment('Albero'),
            'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
            'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
            'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
            'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
            'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

        return true;
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE);
        return true;
    }
}
