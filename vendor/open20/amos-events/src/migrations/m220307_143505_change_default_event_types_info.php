<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\events\models\EventType;
use yii\db\Migration;

/**
 * Class m181102_185505_init_event_types
 */
class m220307_143505_change_default_event_types_info extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        {
        $this->update(EventType::tableName(), 
                ['color' => '#5c6f82','type_icon'=> 'seat'],
                ['event_type' => EventType::TYPE_INFORMATIVE]);
        
        $this->update(EventType::tableName(), 
                ['color' => '#008758','type_icon'=> 'info-outline'],
                ['event_type' => EventType::TYPE_OPEN]);
        
        $this->update(EventType::tableName(), 
                ['color' => '#a66300','type_icon'=> 'account-box-mail'],
                ['event_type' => EventType::TYPE_UPON_INVITATION]);
        
        $this->update(EventType::tableName(), 
                ['color' => '#A61919','type_icon'=> 'ticket-star'],
                ['event_type' => EventType::TYPE_LIMITED_SEATS]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->update(EventType::tableName(), 
                ['color' => '#000000','type_icon'=> 'seat'],
                ['event_type' => EventType::TYPE_INFORMATIVE]);
        
        $this->update(EventType::tableName(), 
                ['color' => '#000000','type_icon'=> 'seat'],
                ['event_type' => EventType::TYPE_OPEN]);
        
        $this->update(EventType::tableName(), 
                ['color' => '#000000','type_icon'=> 'seat'],
                ['event_type' => EventType::TYPE_UPON_INVITATION]);
        
        $this->update(EventType::tableName(), 
                ['color' => '#000000','type_icon'=> 'seat'],
                ['event_type' => EventType::TYPE_LIMITED_SEATS]);
    }
}
