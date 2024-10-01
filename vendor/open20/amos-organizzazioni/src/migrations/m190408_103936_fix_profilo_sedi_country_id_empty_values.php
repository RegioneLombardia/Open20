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
 * Class m190408_103936_fix_profilo_sedi_country_id_empty_values
 */
class m190408_103936_fix_profilo_sedi_country_id_empty_values extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $italiaCountryId = 1;
        $this->update(ProfiloSedi::tableName(), ['country_id' => $italiaCountryId], ['or', ['country_id' => null], ['country_id' => 0]]);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190408_103936_fix_profilo_sedi_country_id_empty_values cannot be reverted.\n";
        return false;
    }
}
