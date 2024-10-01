<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190922_081322_add_fields_sondaggi2
 */
class m190922_081322_add_fields_sondaggi2 extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_MAP     = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {       
        $this->addColumn(self::TABLE, 'url_sondaggio_non_compilabile', $this->text()->defaultValue(null)->after('thank_you_page'));
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {        
        $this->dropColumn(self::TABLE, 'url_sondaggio_non_compilabile');
    }
}