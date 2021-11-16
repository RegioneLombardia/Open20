<?php

use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiRispostePredefinite;

/**
 * Class m210706_232041_add_fields_sondaggi_risposte_predefinite
 */
class m210706_232041_add_fields_sondaggi_risposte_predefinite extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = SondaggiRispostePredefinite::tableName();
    }

    /**, 'code'
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'code', $this->string(8)->after('modello_id'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'code');
        return true;
    }
}
