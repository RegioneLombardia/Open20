<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200317_160813_add_fields_event_landing extends Migration
{
    const TABLE_EVENT_LANDING = "event_landing";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

     $this->addColumn(self::TABLE_EVENT_LANDING, 'social_reg', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'facebook_reg', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'twitter_reg', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'linkedin_reg', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'goolge_reg', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'spid_cns_reg', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'user_name_reg', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_sex', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_age', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_county', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_city', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_telefon', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_fiscal_code', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_company', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'ask_tags', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'url', $this->text()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'luya_page_id', $this->integer()->after('video_slider_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'luya_template_id', $this->integer()->after('video_slider_id'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'social_reg');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'facebook_reg');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'twitter_reg');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'linkedin_reg');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'goolge_reg');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'spid_cns_reg');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'user_name_reg');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_sex');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_age');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_county');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_city');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_telefon');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_fiscal_code');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_company');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'ask_tags');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'url');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'luya_page_id');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'luya_template_id');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
