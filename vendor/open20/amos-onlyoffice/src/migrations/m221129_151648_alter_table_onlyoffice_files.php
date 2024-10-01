<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */
use yii\db\Migration;


/**
 * Description of m221129_151648_alter_table_onlyoffice_files
 */
class m221129_151648_alter_table_onlyoffice_files extends Migration
{
    const NOME_TABELLA = 'onlyoffice_files';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (is_null($this->db->schema->getTableSchema(
            self::NOME_TABELLA, true)))
        {
            echo "Non trovo la tabella '" . self::NOME_TABELLA . "'";
            return false;
        }
        $this->addColumn(self::NOME_TABELLA, 'link_diretto', $this->boolean()
            ->notNull()->defaultValue(1)->after('attach_file_id'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (is_null($this->db->schema->getTableSchema(
            self::NOME_TABELLA, true)))
        {
//            echo "Non trovo la tabella '" . self::NOME_TABELLA . "'";
//            return false;
            return true;
        }
        $this->dropColumn(self::NOME_TABELLA, 'link_diretto');
        return true;
    }
}