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
 * Class m201116_173415_add_columns_event_6
 */
class m201116_173415_add_columns_event_6 extends Migration
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
        $this->addColumn($this->tableName, 'agid_image_slider_id', $this->integer()->null()->defaultValue(null)->after('agid_event_typology_id'));
        $this->addColumn($this->tableName, 'agid_video_slider_id', $this->integer()->null()->defaultValue(null)->after('agid_image_slider_id'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'agid_image_slider_id');
        $this->dropColumn($this->tableName, 'agid_video_slider_id');
        return true;
    }
}
