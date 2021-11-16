<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190922_082322_add_fields_sondaggi3
 */
class m190922_082322_add_fields_sondaggi3 extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_MAP     = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {       
        $this->addColumn(self::TABLE, 'url_chiudi_sondaggio', $this->text()->defaultValue(null)->after('url_sondaggio_non_compilabile'));
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {        
        $this->dropColumn(self::TABLE, 'url_chiudi_sondaggio');
    }
}