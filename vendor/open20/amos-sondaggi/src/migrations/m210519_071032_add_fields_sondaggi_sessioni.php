<?php

use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;

/**
 * Class m210519_071032_add_fields_sondaggi_sessioni
 */
class m210519_071032_add_fields_sondaggi_sessioni extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = SondaggiRisposteSessioni::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status', $this->string(255)->null()->after('id'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'status');
        return true;
    }
}