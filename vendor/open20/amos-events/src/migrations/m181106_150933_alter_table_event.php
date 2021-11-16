<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m181106_150933_alter_table_event
 */
class m181106_150933_alter_table_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(Event::tableName(), 'publication_date_begin', $this->dateTime()->null()->defaultValue(null));
        $this->alterColumn(Event::tableName(), 'publication_date_end', $this->dateTime()->null()->defaultValue(null));
        $this->addColumn(Event::tableName(), 'registration_date_begin', $this->dateTime()->null()->defaultValue(null)->after('publication_date_end'));
        $this->addColumn(Event::tableName(), 'registration_date_end', $this->dateTime()->null()->defaultValue(null)->after('registration_date_begin'));
        $this->addColumn(Event::tableName(), 'show_community', $this->boolean()->notNull()->defaultValue(0)->after('registration_date_end'));
        $this->addColumn(Event::tableName(), 'show_on_frontend', $this->boolean()->notNull()->defaultValue(0)->after('show_community'));
        $this->addColumn(Event::tableName(), 'landing_url', $this->string(255)->null()->after('show_on_frontend'));
        $this->addColumn(Event::tableName(), 'frontend_page_title', $this->string(255)->null()->after('landing_url'));
        $this->addColumn(Event::tableName(), 'frontend_claim', $this->text()->null()->after('frontend_page_title'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Event::tableName(), 'registration_date_begin');
        $this->dropColumn(Event::tableName(), 'registration_date_end');
        $this->dropColumn(Event::tableName(), 'show_community');
        $this->dropColumn(Event::tableName(), 'show_on_frontend');
        $this->dropColumn(Event::tableName(), 'landing_url');
        $this->dropColumn(Event::tableName(), 'frontend_page_title');
        $this->dropColumn(Event::tableName(), 'frontend_claim');
    }

}
