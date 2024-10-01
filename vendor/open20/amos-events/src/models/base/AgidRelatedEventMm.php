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
use yii\helpers\ArrayHelper;

/**
 * Class AgidRelatedEventMm
 *
 * This is the base-model class for table "agid_related_event_mm".
 *
 * @property integer $id
 * @property integer $main_event_id
 * @property integer $related_event_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\Event $mainEvent
 * @property \open20\amos\events\models\Event $relatedEvent
 *
 * @package open20\amos\events\models\base
 */
abstract class AgidRelatedEventMm extends Record
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
        return 'agid_related_event_mm';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['main_event_id', 'related_event_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosEvents::t('amosevents', 'ID'),
            'main_event_id' => AmosEvents::t('amosevents', '#agid_main_event'),
            'related_event_id' => AmosEvents::t('amosevents', '#agid_related_event'),
            'created_at' => AmosEvents::t('amosevents', 'Creato il'),
            'updated_at' => AmosEvents::t('amosevents', 'Aggiornato il'),
            'deleted_at' => AmosEvents::t('amosevents', 'Cancellato il'),
            'created_by' => AmosEvents::t('amosevents', 'Creato da'),
            'updated_by' => AmosEvents::t('amosevents', 'Aggiornato da'),
            'deleted_by' => AmosEvents::t('amosevents', 'Cancellato da'),
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainEvent()
    {
        return $this->hasOne($this->eventsModule->model('Event'), ['id' => 'main_event_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedEvent()
    {
        return $this->hasOne($this->eventsModule->model('Event'), ['id' => 'related_event_id']);
    }
}
