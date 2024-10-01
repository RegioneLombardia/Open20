<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */
use yii\db\Migration;


/**
 * Description of m221205_123859_alter_table_onlyoffice_files
 */
class m221205_123859_alter_table_onlyoffice_files extends Migration
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
        $this->addColumn(self::NOME_TABELLA, 'creato_il', $this->dateTime()
            ->null()->defaultValue(null)->after('last_status'));
        $this->addColumn(self::NOME_TABELLA, 'creato_da', $this->integer(11)
            ->null()->defaultValue(null)->after('creato_il'));
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
        $this->dropColumn(self::NOME_TABELLA, 'creato_il');
        $this->dropColumn(self::NOME_TABELLA, 'creato_da');
        return true;
    }
}