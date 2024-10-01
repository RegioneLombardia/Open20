<?php

use open20\amos\sondaggi\models\SondaggiInvitations;
use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiDomande;

/**
 * Class m210726_113704_add_fields_sondaggi_domande
 */
class m230412_121300_alter_column_count_table_sondaggi_invitations extends Migration
{
    const TABLE = '{{%sondaggi_invitations}}';

    /**, 'code'
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(self::TABLE, 'count', $this->integer()->defaultValue(0)->comment('Number of invited'));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn(self::TABLE, 'count', $this->tinyInteger()->comment('Number of invited'));

        return true;
    }
}
