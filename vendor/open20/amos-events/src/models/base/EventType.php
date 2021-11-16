<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models\base
 * @category   CategoryName
 */

namespace open20\amos\events\models\base;

use open20\amos\events\AmosEvents;
use yii\helpers\ArrayHelper;

/**
 * Class EventType
 * This is the base-model class for table "event_type".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $color
 * @property integer $locationRequested
 * @property integer $durationRequested
 * @property integer $logoRequested
 * @property integer $enabled
 * @property integer $event_type
 * @property integer $limited_seats
 * @property integer $manage_subscritions_queue
 * @property integer $partners
 * @property integer $event_context_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\EventTypeContext $eventTypeContext
 *
 * @package open20\amos\events\models\base
 */
class EventType extends \open20\amos\core\record\Record
{
    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_type';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->eventsModule = AmosEvents::instance();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'color', 'event_context_id'], 'required'],
            [['enabled', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['locationRequested', 'durationRequested', 'logoRequested', 'enabled', 'event_context_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title'], 'string', 'max' => 60],
            [['description'], 'string', 'max' => 2000],
            [['color'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosEvents::t('amosevents', 'ID'),
            'title' => AmosEvents::t('amosevents', 'Title'),
            'description' => AmosEvents::t('amosevents', 'Description'),
            'color' => AmosEvents::t('amosevents', 'Color'),
            'locationRequested' => AmosEvents::t('amosevents', 'Location Requested'),
            'durationRequested' => AmosEvents::t('amosevents', 'Duration Requested'),
            'logoRequested' => AmosEvents::t('amosevents', 'Logo Requested'),
            'enabled' => AmosEvents::t('amosevents', 'Enabled'),
            'event_context_id' => AmosEvents::t('amosevents', 'Event Context ID'),
            'created_at' => AmosEvents::t('amosevents', 'Created At'),
            'updated_at' => AmosEvents::t('amosevents', 'Updated At'),
            'deleted_at' => AmosEvents::t('amosevents', 'Deleted At'),
            'created_by' => AmosEvents::t('amosevents', 'Created By'),
            'updated_by' => AmosEvents::t('amosevents', 'Updated By'),
            'deleted_by' => AmosEvents::t('amosevents', 'Deleted By')
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventTypeContext()
    {
        return $this->hasOne($this->eventsModule->model('EventTypeContext'), ['id' => 'event_context_id']);
    }
}
