<?php

namespace open20\amos\events\models\base;

use Yii;

/**
 * This is the base-model class for table "event_landing_protagonist".
 *
 * @property integer $id
 * @property integer $event_landing_id
 * @property string $name
 * @property string $surname
 * @property string $company
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\EventLanding $eventLanding
 */
class  EventLandingProtagonist extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_landing_protagonist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname'], 'required'],
            [['event_landing_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'surname', 'company'], 'string', 'max' => 255],
            [['event_landing_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventLanding::className(), 'targetAttribute' => ['event_landing_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'event_landing_id' => Yii::t('amosevents', 'Landing'),
            'name' => Yii::t('amosevents', 'Name'),
            'surname' => Yii::t('amosevents', 'Surname'),
            'company' => Yii::t('amosevents', 'Company'),
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
    public function getEventLanding()
    {
        return $this->hasOne(\open20\amos\events\models\EventLanding::className(), ['id' => 'event_landing_id']);
    }
}
