<?php

use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;

/**
 * Class m210805_113704_add_fields_sondaggi
 */
class m210805_113704_add_fields_sondaggi extends Migration
{
    private $tableName;
    private $tableName2;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = Sondaggi::tableName();
        $this->tableName2 = SondaggiRisposteSessioni::tableName();
    }

    /**, 'code'
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'use_get_language', $this->integer()->defaultValue(0)->after('forza_lingua'));
        $this->addColumn($this->tableName, 'field_extra', $this->integer()->defaultValue(0)->after('use_get_language'));
        $this->addColumn($this->tableName2, 'lang', $this->string()->null()->after('user_id'));
        $this->addColumn($this->tableName2, 'field_extra', $this->string()->null()->after('lang'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'use_get_language');
        $this->dropColumn($this->tableName, 'field_extra');
        $this->dropColumn($this->tableName2, 'lang');
        $this->dropColumn($this->tableName2, 'field_extra');
        return true;
    }
}
