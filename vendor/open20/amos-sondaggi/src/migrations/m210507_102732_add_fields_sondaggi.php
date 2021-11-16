<?php

use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\base\Sondaggi;

/**
 * Class m210507_102732_add_fields_sondaggi
 */
class m210507_102732_add_fields_sondaggi extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = Sondaggi::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'sottotitolo', $this->text()->null()->defaultValue(null)->after('titolo'));
        $this->addColumn($this->tableName, 'publish_date', $this->date());
        $this->addColumn($this->tableName, 'close_date', $this->date()->after('publish_date'));
        $this->addColumn($this->tableName, 'send_pdf_to_compiler', $this->boolean()->defaultValue(0)->after('send_pdf_via_email'));
        $this->addColumn($this->tableName, 'send_pdf_via_email_closed', $this->boolean()->defaultValue(0)->after('send_pdf_to_compiler'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'sottotitolo');
        return true;
    }
}