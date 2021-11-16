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


class m201116_155000_add_fk_to_agid_document_type extends Migration
{
    public function up()
    {
        // addColumn
        $this->addColumn('documenti_agid_type', 'agid_document_content_type_id', $this->integer()->null()->defaultValue(null));

        // addForeignKey
        $this->addForeignKey(
            'fk-agid-document-content-type-id',
            'documenti_agid_type',
            'agid_document_content_type_id',
            'documenti_agid_content_type',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ('fk-agid-document-content-type-id', 'documenti_agid_type');
        // dropColumn
        $this->dropColumn('documenti_agid_type', 'agid_document_content_type_id');
    }

}