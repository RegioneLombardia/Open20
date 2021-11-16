<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200805_164913_add_fields_event_landing_required extends Migration
{
    const TABLE_EVENT_LANDING = "event_landing";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_sex_required', $this->integer(1)->defaultValue(0)->after('ask_sex'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_age_required', $this->integer(1)->defaultValue(0)->after('ask_sex'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_county_required', $this->integer(1)->after('ask_sex'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_city_required', $this->integer(1)->defaultValue(0)->after('ask_sex'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_telefon_required', $this->integer(1)->after('ask_sex'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_fiscal_code_required', $this->integer(1)->defaultValue(0)->after('ask_sex'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_company_required', $this->integer(1)->defaultValue(0)->after('ask_sex'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_tags_required', $this->integer(1)->defaultValue(0)->after('ask_sex'));
 
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_sex_required');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_age_required');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_county_required');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_city_required');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_telefon_required');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_fiscal_code_required');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_company_required');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_tags_required');


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
