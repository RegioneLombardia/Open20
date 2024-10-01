<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models\base
 * @category   CategoryName
 */

namespace open20\amos\events\models\base;

use open20\amos\core\record\Record;
use open20\amos\events\AmosEvents;

/**
 * Class EventRoom
 *
 * This is the base-model class for table "event_room".
 *
 * @property integer $id
 * @property string $room_name
 * @property integer $available_seats
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\Event[] $events
 *
 * @package open20\amos\events\models\base
 */
abstract class EventRoom extends Record
{
    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->eventsModule = AmosEvents::instance();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_name', 'available_seats'], 'required'],
            [['room_name'], 'string', 'max' => 255],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['available_seats'], 'integer', 'min' => 1],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosEvents::t('amosevents', 'ID'),
            'room_name' => AmosEvents::t('amosevents', '#room_name'),
            'available_seats' => AmosEvents::t('amosevents', '#available_seats'),
            'created_at' => AmosEvents::t('amosevents', 'Created At'),
            'updated_at' => AmosEvents::t('amosevents', 'Updated At'),
            'deleted_at' => AmosEvents::t('amosevents', 'Deleted At'),
            'created_by' => AmosEvents::t('amosevents', 'Created By'),
            'updated_by' => AmosEvents::t('amosevents', 'Updated By'),
            'deleted_by' => AmosEvents::t('amosevents', 'Deleted By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany($this->eventsModule->model('Event'), ['event_room_id' => 'id']);
    }
}
