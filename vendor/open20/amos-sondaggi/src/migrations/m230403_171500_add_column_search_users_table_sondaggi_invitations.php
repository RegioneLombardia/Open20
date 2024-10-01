<?php

use open20\amos\sondaggi\models\SondaggiInvitations;
use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiDomande;

/**
 * Class m210726_113704_add_fields_sondaggi_domande
 */
class m230403_171500_add_column_search_users_table_sondaggi_invitations extends Migration
{
    const TABLE = '{{%sondaggi_invitations}}';

    /**, 'code'
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            self::TABLE,
            'search_users',
            $this->text()
                ->null()
                ->after('filter_type')
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'search_users');

        return true;
    }
}
