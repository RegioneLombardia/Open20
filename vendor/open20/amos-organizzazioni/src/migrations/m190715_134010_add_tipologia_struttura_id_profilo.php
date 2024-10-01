<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\Profilo;
use yii\db\Migration;

/**
 * Class m181011_131256_add_profilo_table_fields_istat_code_profilo_enti_type_id
 */
class m190715_134010_add_tipologia_struttura_id_profilo extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = Profilo::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            $this->tableName, 
            'tipologia_struttura_id', 
            $this->integer()->notNull()->defaultValue(null)->after('profilo_enti_type_id')->comment('Tipologia di Struttura Id')
        );
        
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'tipologia_struttura_id');
        
        return true;
    }
}
