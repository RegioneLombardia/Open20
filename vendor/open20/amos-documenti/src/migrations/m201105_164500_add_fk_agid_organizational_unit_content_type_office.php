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
use open20\amos\documenti\models\Documenti;


class m201105_164500_add_fk_agid_organizational_unit_content_type_office extends Migration
{ 

    public function safeUp()
    {
        // addColumn to AGID ORGANIZATIONAL with AGID CONTENT TYPE "office"
        $table = $this->db->schema->getTableSchema(Documenti::tableName());
        if (!isset($table->columns['agid_organizational_unit_content_type_office_id'])) {
            $this->addColumn(Documenti::tableName(), 'agid_organizational_unit_content_type_office_id', $this->integer()->null()->defaultValue(null));
            //->comment('FK agid_organizational_unit with agid_content_type_id => Area');
        }
        // addForeignKey
        if ($this->db->schema->getTableSchema('agid_organizational_unit', true) === null) {
            $this->execute('SET FOREIGN_KEY_CHECKS=0');
            $this->addForeignKey(
                'fk-agid-organizational-unit-content-type-office-id',
                Documenti::tableName(),
                'agid_organizational_unit_content_type_office_id',
                'agid_organizational_unit',
                'id'
            );
            $this->execute('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function safeDown()
    {
        // dropForeignKey
        $this->dropForeignKey ( 'fk-agid-organizational-unit-content-type-office-id', 'documenti' );
        // dropColumn
        $this->dropColumn('documenti', 'agid_organizational_unit_content_type_office_id');
    }

}