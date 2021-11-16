<?php

namespace open20\amos\events\models\base;

use Yii;

/**
 * This is the base-model class for table "event_location".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $is_location_streaming
 * @property string $event_place_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\EventLocationEntrances[] $eventLocationEntrances
 */
abstract class  EventLocation extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['hidden', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by','is_location_streaming'], 'integer'],
            [['name', 'description', 'event_place_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'name' => Yii::t('amosevents', 'Name'),
            'description' => Yii::t('amosevents', 'Description'),
            'event_place_id' => Yii::t('amosevents', 'Address'),
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
    public function getEventLocationEntrances()
    {
        return $this->hasMany(\open20\amos\events\models\EventLocationEntrances::className(), ['event_location_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventPlaces()
    {
        return $this->hasOne(\open20\amos\events\models\EventPlaces::className(), ['place_id' => 'event_place_id']);
    }
}
