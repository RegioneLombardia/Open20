<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

use yii\db\Migration;

class m180315_153030_alter_column_profile extends Migration
{

    const TABLE_PROFILO = '{{%profilo}}';

    public function safeUp()
    {

        $this->alterColumn('profilo', 'telefono', 'string');
        $this->alterColumn('profilo', 'fax', 'string');
        $this->alterColumn('profilo', 'sede_legale_telefono', 'string');
        $this->alterColumn('profilo', 'sede_legale_fax', 'string');



    }

    public function safeDown()
    {
        $this->alterColumn('profilo', 'telefono', 'integer');
        $this->alterColumn('profilo', 'fax', 'integer');
        $this->alterColumn('profilo', 'sede_legale_telefono', 'integer');
        $this->alterColumn('profilo', 'sede_legale_fax', 'integer');

    }


}
