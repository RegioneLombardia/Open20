<?php

use open20\amos\sondaggi\models\SondaggiInvitations;
use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiDomande;

/**
 * Class m210726_113704_add_fields_sondaggi_domande
 */
class m230403_155700_add_column_target_table_sondaggi_invitations extends Migration
{
    const TABLE = '{{%sondaggi_invitations}}';

    /**, 'code'
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            self::TABLE,
            'target',
            $this->integer()
                ->defaultValue(SondaggiInvitations::TARGET_ORGANIZATIONS)
                ->after('name'));

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
