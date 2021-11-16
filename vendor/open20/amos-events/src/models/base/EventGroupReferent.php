<?php

namespace open20\amos\events\models\base;

use Yii;

/**
 * This is the base-model class for table "event_group_referent".
 *
 * @property integer $id
 * @property string $denominazione
 * @property string $descrizione
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\EventGroupReferentMm[] $eventGroupReferentMms
 */
class EventGroupReferent extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_group_referent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descrizione', 'note'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['denominazione'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'denominazione' => Yii::t('amosevents', 'Denominazione'),
            'descrizione' => Yii::t('amosevents', 'Descrizione'),
            'note' => Yii::t('amosevents', 'Note'),
            'created_at' => Yii::t('amosevents', 'Created at'),
            'updated_at' => Yii::t('amosevents', 'Updated at'),
            'deleted_at' => Yii::t('amosevents', 'Deleted at'),
            'created_by' => Yii::t('amosevents', 'Created by'),
            'updated_by' => Yii::t('amosevents', 'Updated at'),
            'deleted_by' => Yii::t('amosevents', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventGroupReferentMms()
    {
        return $this->hasMany(\open20\amos\events\models\EventGroupReferentMm::className(),
                ['event_group_referent_id' => 'id']);
    }
}