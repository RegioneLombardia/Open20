<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m201111_160052_add_columns_event_5
 */
class m201111_160052_add_columns_event_5 extends Migration
{
    private $tableName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = Event::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'agid_event_typology_id', $this->smallInteger()->unsigned()->null()->defaultValue(null)->after('event_room_id'));
        $this->addColumn($this->tableName, 'agid_description', $this->string(255)->null()->defaultValue(null)->after('summary'));
        $this->addColumn($this->tableName, 'agid_georeferencing', $this->string(255)->null()->defaultValue(null)->after('agid_description'));
        $this->addColumn($this->tableName, 'agid_dates_and_hours', $this->text()->null()->defaultValue(null)->after('agid_georeferencing'));
        $this->addColumn($this->tableName, 'agid_price', $this->text()->null()->defaultValue(null)->after('agid_dates_and_hours'));
        $this->addColumn($this->tableName, 'agid_document_id', $this->integer()->null()->defaultValue(null)->after('agid_price'));
        $this->addColumn($this->tableName, 'agid_organized_by', $this->text()->null()->defaultValue(null)->after('agid_document_id'));
        $this->addColumn($this->tableName, 'agid_contact', $this->text()->null()->defaultValue(null)->after('agid_organized_by'));
        $this->addColumn($this->tableName, 'agid_website', $this->string(255)->null()->defaultValue(null)->after('agid_contact'));
        $this->addColumn($this->tableName, 'agid_other_informations', $this->text()->null()->defaultValue(null)->after('agid_website'));
        $this->addColumn($this->tableName, 'agid_geolocation', $this->string(255)->null()->defaultValue(null)->after('agid_other_informations'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'agid_event_typology_id');
        $this->dropColumn($this->tableName, 'agid_description');
        $this->dropColumn($this->tableName, 'agid_georeferencing');
        $this->dropColumn($this->tableName, 'agid_dates_and_hours');
        $this->dropColumn($this->tableName, 'agid_price');
        $this->dropColumn($this->tableName, 'agid_document_id');
        $this->dropColumn($this->tableName, 'agid_organized_by');
        $this->dropColumn($this->tableName, 'agid_contact');
        $this->dropColumn($this->tableName, 'agid_website');
        $this->dropColumn($this->tableName, 'agid_other_informations');
        return true;
    }
}
