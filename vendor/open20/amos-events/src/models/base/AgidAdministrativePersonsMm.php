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

use open20\amos\core\module\AmosModule;
use open20\amos\core\record\Record;
use open20\amos\events\AmosEvents;
use yii\helpers\ArrayHelper;

/**
 * Class AgidAdministrativePersonsMm
 *
 * This is the base-model class for table "agid_event_administrative_persons_mm".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $person_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\Event $event
 * @property \open20\agid\person\models\AgidPerson $person
 *
 * @package open20\amos\events\models\base
 */
abstract class AgidAdministrativePersonsMm extends Record
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
        return 'agid_event_administrative_persons_mm';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'person_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
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
            'event_id' => AmosEvents::t('amosevents', 'Event'),
            'person_id' => AmosEvents::t('amosevents', '#agid_person'),
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
    public function getEvent()
    {
        return $this->hasOne($this->eventsModule->model('Event'), ['id' => 'event_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        /** @var \open20\agid\person\Module|AmosModule $agidPersonModule */
        $agidPersonModule = \Yii::$app->getModule('person');
        if (is_null($agidPersonModule)) {
            return null;
        }
        return $this->hasOne($agidPersonModule->getModelClassName(), ['id' => 'person_id']);
    }
}
