<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m200908_110500_log_azioni_cron extends \yii\db\Migration
{
    const TABLE = '{{%log_azioni_cron}}';
    private $tableName;

    public function init()
    {
        parent::init();
        $this->tableName = $this->db->getSchema()->getRawTableName(self::TABLE);
    }

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            try {
                $this->createTable(self::TABLE, [
                    'id' => $this->primaryKey(11),
                    'controller_id' => $this->text()->defaultValue(null)->comment('Controller ID'),
                    'action_id' => $this->text()->defaultValue(null)->comment('Action ID'),
                    'start' => $this->boolean()->defaultValue(false)->comment('Action ID'),
                    'end' => $this->boolean()->defaultValue(false)->comment('Action ID'),
                    'durata' => $this->integer(11)->defaultValue(null)->comment('Durata dell azione (millisecondi)'),
                    'messaggio' => $this->text()->defaultValue(null)->comment('Messaggio di log'),
                    'created_at' => $this->dateTime()->null()->defaultValue(null),
                    'updated_at' => $this->dateTime()->null()->defaultValue(null),
                    'deleted_at' => $this->dateTime()->null()->defaultValue(null),
                    'created_by' => $this->integer()->null()->defaultValue(null),
                    'updated_by' => $this->integer()->null()->defaultValue(null),
                    'deleted_by' => $this->integer()->null()->defaultValue(null),
                ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            } catch (Exception $e) {
                echo "Errore durante la creazione della tabella " . $this->tableName . "\n";
                echo $e->getMessage() . "\n";
                return false;
            }
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella " . $this->tableName . " esiste gia'\n";
        }

        return true;
    }

    public function safeDown()
    {
        try {
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
            $this->dropTable(self::TABLE);
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        } catch (Exception $e) {
            echo "Errore durante la cancellazione della tabella " . $this->tableName . "\n";
            echo $e->getMessage() . "\n";
            return false;
        }

        return true;
    }

}