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
 * Class AgidEventDocumentsMm
 *
 * This is the base-model class for table "agid_event_documents_mm".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $document_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\Event $event
 * @property \open20\amos\documenti\models\Documenti $document
 *
 * @package open20\amos\events\models\base
 */
abstract class AgidEventDocumentsMm extends Record
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
        return 'agid_event_documents_mm';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'document_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
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
            'document_id' => AmosEvents::t('amosevents', '#agid_document'),
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
    public function getDocument()
    {
        /** @var \open20\amos\documenti\AmosDocumenti $documentiModule */
        $documentiModule = \Yii::$app->getModule('documenti');
        if (is_null($documentiModule)) {
            return null;
        }
        return $this->hasOne($documentiModule->getModelClassName(), ['id' => 'document_id']);
    }
}
