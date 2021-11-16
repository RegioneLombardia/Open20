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


class m201105_170900_add_columns_agid_to_documenti extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti', 'object', $this->text()->null()->defaultValue(null)->comment('Object') );
        $this->addColumn( 'documenti', 'extended_description', $this->text()->null()->defaultValue(null)->comment('Extended Description') );
        $this->addColumn( 'documenti', 'distribution_proscription', $this->text()->null()->defaultValue(null)->comment('Distribution proscription') );
        $this->addColumn( 'documenti', 'start_date', $this->date()->null()->defaultValue(null)->comment('Initial date and stage. For example, opening date for participation in a call') );
        $this->addColumn( 'documenti', 'dates_and_intermediate_stages', $this->text()->null()->defaultValue(null)->comment('Dates and intermediate stages') );
        $this->addColumn( 'documenti', 'end_date', $this->date()->null()->defaultValue(null)->comment('Provide an expiration date for the document content. Example date of communication of the winners of the competition') );
        $this->addColumn( 'documenti', 'further_information', $this->text()->null()->defaultValue(null)->comment('Learn more about the document') );
        $this->addColumn( 'documenti', 'regulatory_requirements', $this->text()->null()->defaultValue(null)->comment('List of links with normative references useful for the document') );
        $this->addColumn( 'documenti', 'protocol', $this->text()->null()->defaultValue(null)->comment('Protocol number of the document') );
        $this->addColumn( 'documenti', 'protocol_date', $this->date()->null()->defaultValue(null)->comment('Protocol date of the document') );
        $this->addColumn( 'documenti', 'help_box', $this->text()->null()->defaultValue(null)->comment('Any support contacts for the user. For example the references of the URP') );

    }

    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti', 'object');
        $this->dropColumn('documenti', 'extended_description');
        $this->dropColumn('documenti', 'distribution_proscription');
        $this->dropColumn('documenti', 'start_date');
        $this->dropColumn('documenti', 'dates_and_intermediate_stages');
        $this->dropColumn('documenti', 'end_date');
        $this->dropColumn('documenti', 'further_information');
        $this->dropColumn('documenti', 'regulatory_requirements');
        $this->dropColumn('documenti', 'protocol');
        $this->dropColumn('documenti', 'protocol_date');
        $this->dropColumn('documenti', 'help_box');
    }
}