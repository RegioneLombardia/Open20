<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitContentType;



class m210712_143000_add_agid_organizational_unit_content_type_field extends Migration
{

    public function safeUp() {
        $this->addColumn(AgidOrganizationalUnitContentType::tableName(),'content_type_icon', $this->string(255)->null()->defaultValue(null)->after('description'));
        return true;
    }

    public function safeDown() {
        $this->dropColumn(AgidOrganizationalUnitContentType::tableName(),'content_type_icon');
        return true;
    }

}