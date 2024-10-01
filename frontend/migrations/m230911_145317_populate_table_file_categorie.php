<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */

use yii\db\Migration;

/**
 * Class m230911_145317_populate_table_file_categorie
 */
class m230911_145317_populate_table_file_categorie extends Migration
{
    const NOME_TABELLA = 'attach_file_categorie';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (is_null($this->db->schema->getTableSchema(self::NOME_TABELLA,
            true))) {
            echo "Non trovo la tabella '" . self::NOME_TABELLA . "'";
            return false;
        }

        $adesso = date('Y-m-d H:i:s');
        $this->batchInsert(self::NOME_TABELLA, ['titolo', 'created_at',
            'updated_at', 'created_by', 'updated_by'], [
                ['Normative', $adesso, $adesso, 1, 1],
                ['Documenti', $adesso, $adesso, 1, 1],
            ]);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if (is_null($this->db->schema->getTableSchema(self::NOME_TABELLA,
            true))) {
//            echo "Non trovo la tabella '".self::NOME_TABELLA."'";
            return true;
        }

        $this->delete(self::NOME_TABELLA, ['titolo' => ['Normative',
            'Documenti']]);
        return true;
    }
}