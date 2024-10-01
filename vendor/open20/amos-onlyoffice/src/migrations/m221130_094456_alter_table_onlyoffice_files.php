<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */
use yii\db\Migration;


/**
 * Description of m221130_094456_alter_table_onlyoffice_files
 */
class m221130_094456_alter_table_onlyoffice_files extends Migration
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
        $this->addColumn(self::NOME_TABELLA, 'in_modifica', $this->boolean()
            ->notNull()->defaultValue(0)->after('link_diretto'));
        $this->addColumn(self::NOME_TABELLA, 'last_status', $this
            ->tinyInteger()->unsigned()->null()->defaultValue(null)
            ->after('in_modifica'));
        $this->addColumn(self::NOME_TABELLA, 'open_in_mod_at', $this->dateTime()
            ->null()->defaultValue(null)->after('last_status'));
        $this->addColumn(self::NOME_TABELLA, 'open_in_mod_by',
            $this->integer(11)->null()->defaultValue(null)
            ->after('open_in_mod_at'));
        $this->addColumn(self::NOME_TABELLA, 'modified_at', $this->dateTime()
            ->null()->defaultValue(null)->after('open_in_mod_by'));
        $this->addColumn(self::NOME_TABELLA, 'modified_by', $this->integer(11)
            ->null()->defaultValue(null)->after('modified_at'));
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
        $this->dropColumn(self::NOME_TABELLA, 'in_modifica');
        $this->dropColumn(self::NOME_TABELLA, 'last_status');
        $this->dropColumn(self::NOME_TABELLA, 'open_in_mod_at');
        $this->dropColumn(self::NOME_TABELLA, 'open_in_mod_by');
        $this->dropColumn(self::NOME_TABELLA, 'modified_at');
        $this->dropColumn(self::NOME_TABELLA, 'modified_by');
        return true;
    }
}