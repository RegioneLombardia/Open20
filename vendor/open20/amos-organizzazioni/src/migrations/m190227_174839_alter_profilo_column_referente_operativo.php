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
 * Class m190227_174839_alter_profilo_column_referente_operativo
 */
class m190227_174839_alter_profilo_column_referente_operativo extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update(Profilo::tableName(), ['rappresentante_legale' => null], ['rappresentante_legale' => '']);
        $this->alterColumn(Profilo::tableName(), 'referente_operativo', $this->string()->defaultValue(null));
        $this->update(Profilo::tableName(), ['referente_operativo' => null], ['referente_operativo' => 0]);

        $this->alterColumn(Profilo::tableName(), 'referente_operativo', $this->integer()->defaultValue(null)->comment('Referente operativo'));
        
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn(Profilo::tableName(), 'referente_operativo', $this->string(255)->defaultValue(null)->comment('Referente operativo'));
        return true;
    }
}
