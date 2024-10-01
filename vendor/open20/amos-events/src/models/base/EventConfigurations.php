<?php

namespace open20\amos\events\models\base;

use Yii;

/**
 * This is the base-model class for table "event_configurations".
 *
 * @property integer $id
 * @property integer $from_days_to_now_visibility
 */
class  EventConfigurations extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_configurations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_days_to_now_visibility'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'from_days_to_now_visibility' => Yii::t('amosevents', 'Days visibility'),
        ];
    }
}
