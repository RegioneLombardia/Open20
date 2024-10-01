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

class m171207_115459_add_profile_user_mm extends Migration
{



    const TABLE_ORGANIZATIONS_USER_MM = '{{%profilo_user_mm}}';
    const TABLE_USER_AMOS = '{{%user}}';
    const TABLE_ORGANIZATIONS = '{{%profilo}}';

    public function safeUp()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");

        $charset = ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);
        $auto_increment = ($this->db->driverName === 'mysql' ? ' AUTO_INCREMENT=1' : null);

        if ($this->db->schema->getTableSchema(self::TABLE_ORGANIZATIONS_USER_MM, true) === null) {
            $this->createTable(self::TABLE_ORGANIZATIONS_USER_MM, [
                'id' => $this->primaryKey(),
                'profilo_id' => Schema::TYPE_INTEGER . "(11) NULL COMMENT 'Organizzazione'",
                'user_id' => Schema::TYPE_INTEGER . "(11) NULL COMMENT 'Utente'",
                'status' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Stato'",
                'role' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Ruolo'",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
            ]);

            $this->addForeignKey(
                "fk_" . $this->cleanTableName(self::TABLE_ORGANIZATIONS_USER_MM) . "_profilo_id",
                self::TABLE_ORGANIZATIONS_USER_MM,
                "profilo_id",
                self::TABLE_ORGANIZATIONS,
                "id"
            );

            $this->addForeignKey(
                "fk_" . $this->cleanTableName(self::TABLE_ORGANIZATIONS_USER_MM) . "_user_id",
                self::TABLE_ORGANIZATIONS_USER_MM,
                "user_id",
                self::TABLE_USER_AMOS,
                "id"
            );
        }



        $this->execute("SET FOREIGN_KEY_CHECKS=1;");
    }

    public function safeDown()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");
        if ($this->db->schema->getTableSchema(self::TABLE_ORGANIZATIONS_USER_MM, true) !== null) {
            $this->dropTable(self::TABLE_ORGANIZATIONS_USER_MM);
        }
        $this->execute("SET FOREIGN_KEY_CHECKS=1;");
    }

    /**
     * @param $table_name
     * @return mixed
     */
    private function cleanTableName($table_name)
    {
        return str_replace(["{{%", "}}"], ["", ""], $table_name);
    }
}
