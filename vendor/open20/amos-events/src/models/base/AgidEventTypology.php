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
 * Class AgidEventTypology
 *
 * This is the base-model class for table "agid_event_typology".
 *
 * @property integer $id
 * @property string $name
 * @property integer $level
 * @property integer $parent_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\Event[] $events
 * @property \open20\amos\events\models\AgidEventTypology $parent
 *
 * @package open20\amos\events\models\base
 */
abstract class AgidEventTypology extends Record
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
        return 'agid_event_typology';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['level', 'parent_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosEvents::t('amosevents', 'ID'),
            'name' => AmosEvents::t('amosevents', '#agid_tipology'),
            'level' => AmosEvents::t('amosevents', '#level'),
            'parent_id' => AmosEvents::t('amosevents', '#parent_id'),
            'created_at' => AmosEvents::t('amosevents', 'Created At'),
            'updated_at' => AmosEvents::t('amosevents', 'Updated At'),
            'deleted_at' => AmosEvents::t('amosevents', 'Deleted At'),
            'created_by' => AmosEvents::t('amosevents', 'Created By'),
            'updated_by' => AmosEvents::t('amosevents', 'Updated By'),
            'deleted_by' => AmosEvents::t('amosevents', 'Deleted By')
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany($this->eventsModule->model('Event'), ['event_agid_typology_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne($this->eventsModule->model('AgidEventTypology'), ['id' => 'parent_id']);
    }
}
