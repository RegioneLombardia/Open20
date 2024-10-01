<?php

use open20\amos\sondaggi\models\SondaggiInvitations;
use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiDomande;

/**
 * Class m210726_113704_add_fields_sondaggi_domande
 */
class m230407_094900_add_column_target_table_sondaggi_communication extends Migration
{
    const TABLE = '{{%sondaggi_communication}}';

    /**, 'code'
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            self::TABLE,
            'target',
            $this->integer()->defaultValue(SondaggiInvitations::TARGET_ORGANIZATIONS)->after('sondaggi_id'));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'target');

        return true;
    }
}
