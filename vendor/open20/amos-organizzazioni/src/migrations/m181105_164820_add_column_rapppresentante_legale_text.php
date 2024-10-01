<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\Profilo;
use yii\db\Migration;

/**
 * Class m181011_090220_create_table_profilo_enti_type
 */
class m181105_164820_add_column_rapppresentante_legale_text extends Migration
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
        $table = \Yii::$app->db->schema->getTableSchema($this->tableName);
        if (!isset($table->columns['rappresentante_legale_text'])) {
            $this->addColumn($this->tableName, 'rappresentante_legale_text', $this->string()->after('rappresentante_legale')->comment('Rappresentante legale text'));
        }
        
        $this->alterColumn($this->tableName, 'rappresentante_legale', $this->string()->defaultValue(null));
        $this->update($this->tableName, ['rappresentante_legale' => null], ['rappresentante_legale' => '']);
        $this->alterColumn($this->tableName, 'rappresentante_legale', $this->integer()->defaultValue(null)->comment('Rappresentante legale'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'rappresentante_legale_text');
        $this->alterColumn($this->tableName, 'rappresentante_legale', $this->integer()->notNull()->comment('Rappresentante legale'));
        return true;
    }
}
