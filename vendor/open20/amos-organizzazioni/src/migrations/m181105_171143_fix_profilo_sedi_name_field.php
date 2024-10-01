<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\ProfiloSedi;
use yii\db\Migration;

/**
 * Class m181105_171143_fix_profilo_sedi_name_field
 */
class m181105_171143_fix_profilo_sedi_name_field extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn(ProfiloSedi::tableName(), 'name', $this->string(255)->notNull());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn(ProfiloSedi::tableName(), 'name', $this->string(100)->notNull());
    }
}
