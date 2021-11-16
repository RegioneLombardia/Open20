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


class m201106_110000_add_fk_documenti_agid_content_type_id_to_documenti extends Migration
{
    public function up()
    {
        // addColumn
        $this->addColumn('documenti', 'documenti_agid_content_type_id', $this->integer()->null()->defaultValue(null));

        // addForeignKey
        $this->addForeignKey(
            'fk-documenti-agid-content-type-id',
            'documenti',
            'documenti_agid_content_type_id',
            'documenti_agid_content_type',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ('fk-documenti-agid-content-type-id', 'documenti');
        // dropColumn
        $this->dropColumn('documenti', 'documenti_agid_content_type_id');
    }

}