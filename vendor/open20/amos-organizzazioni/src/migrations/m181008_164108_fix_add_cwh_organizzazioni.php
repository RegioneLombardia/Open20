<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;

/**
 * Class m181008_164108_fix_add_cwh_organizzazioni
 */
class m181008_164108_fix_add_cwh_organizzazioni extends Migration
{
    private $tablename = 'cwh_config';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableSchema = $this->db->schema->getTableSchema($this->tablename);
        $rawSqlColumn = $tableSchema->getColumn('raw_sql');
        if (!is_null($rawSqlColumn)) {
            MigrationCommon::printConsoleMessage('La colonna raw_sql esiste nella tabella ' . $this->tablename . '. Aggiorno il valore.');
            try {
                $this->update($this->tablename, ['raw_sql' => "select concat('profilo-',`profilo`.`id`) AS `id`, 7 AS `cwh_config_id`, `profilo`.`id` AS `record_id`, 'open20\\amos\\organizzazioni\\models\\Profilo' AS `classname`, 1 AS `visibility`, `profilo`.`created_at` AS `created_at`, `profilo`.`updated_at` AS `updated_at`, `profilo`.`deleted_at` AS `deleted_at`, `profilo`.`created_by` AS `created_by`, `profilo`.`updated_by` AS `updated_by`, `profilo`.`deleted_by` AS `deleted_by` from `profilo`"], ['id' => 7]);
            } catch (\Exception $e) {
                MigrationCommon::printConsoleMessage("Errore durante l'aggiornamento della configurazione CWH per amos-organizzazioni");
                MigrationCommon::printConsoleMessage($e->getMessage());
                return false;
            }
        } else {
            MigrationCommon::printConsoleMessage('La colonna raw_sql non esiste nella tabella ' . $this->tablename . '. Nulla da modificare.');
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m181008_164349_fix_add_cwh_organizzazioni cannot be reverted.\n";
        return false;
    }
}
