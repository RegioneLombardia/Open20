<?php

namespace open20\amos\events\models\base;

use Yii;

/**
 * This is the base-model class for table "event_location_entrances".
 *
 * @property integer $id
 * @property integer $event_location_id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\events\models\EventLocation $eventLocation
 */
abstract class  EventLocationEntrances extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_location_entrances';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['event_location_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'description'], 'string', 'max' => 255],
//            [['event_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\events\models\EventLocation::className(), 'targetAttribute' => ['event_location_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'event_location_id' => Yii::t('amosevents', 'Location'),
            'name' => Yii::t('amosevents', 'Name'),
            'description' => Yii::t('amosevents', 'Description'),
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
    public function getEventLocation()
    {
        return $this->hasOne(\open20\amos\events\models\EventLocation::className(), ['id' => 'event_location_id']);
    }
}
