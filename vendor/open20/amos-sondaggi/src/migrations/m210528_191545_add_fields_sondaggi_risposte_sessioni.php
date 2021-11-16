<?php

use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;

/**
 * Class m210528_191545_add_fields_sondaggi_risposte_sessioni
 */
class m210528_191545_add_fields_sondaggi_risposte_sessioni extends Migration
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

    /**, 'filter_type','search_tags', 'search_groups', 'field', 'value', 'include_exclude''
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'organization_id', $this->integer()->null()->after('user_id'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'organization_id');
        return true;
    }
}