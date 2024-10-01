<?php

use open20\amos\sondaggi\models\SondaggiInvitations;
use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiDomande;

/**
 * Class m210726_113704_add_fields_sondaggi_domande
 */
class m230406_105500_add_column_community_id_table_sondaggi extends Migration
{
    const TABLE = '{{%sondaggi}}';

    /**, 'code'
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            self::TABLE,
            'community_id',
            $this->integer()->null()->after('abilita_registrazione'));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'community_id');

        return true;
    }
}
