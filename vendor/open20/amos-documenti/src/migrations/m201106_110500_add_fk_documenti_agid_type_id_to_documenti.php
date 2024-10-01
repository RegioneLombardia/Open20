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


class m201106_110500_add_fk_documenti_agid_type_id_to_documenti extends Migration
{
    public function up()
    {
        // addColumn
        $this->addColumn('documenti', 'documenti_agid_type_id', $this->integer()->null()->defaultValue(null));

        // addForeignKey
        $this->addForeignKey(
            'fk-documenti-agid-type-id',
            'documenti',
            'documenti_agid_type_id',
            'documenti_agid_type',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ('fk-documenti-agid-type-id', 'documenti');
        // dropColumn
        $this->dropColumn('documenti', 'documenti_agid_type_id');
    }

}