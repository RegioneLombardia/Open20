<?php

use open20\amos\sondaggi\models\SondaggiInvitations;
use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiDomande;

/**
 * Class m210726_113704_add_fields_sondaggi_domande
 */
class m230412_092200_insert_sondaggi_invitations_table_models_classname extends Migration
{
    const TABLE = '{{%models_classname}}';

    /**, 'code'
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(self::TABLE, [
            'classname' => SondaggiInvitations::className(),
            'table' => SondaggiInvitations::tableName(),
            'module' => 'sondaggi',
            'label' => 'SondaggiInvitations'
        ]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(self::TABLE, [
            'classname' => SondaggiInvitations::className(),
            'table' => SondaggiInvitations::tableName(),
            'module' => 'sondaggi',
            'label' => 'SondaggiInvitations'
        ]);

        return true;
    }
}
