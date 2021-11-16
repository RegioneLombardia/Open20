<?php

namespace open20\amos\events\models\base;

use open20\amos\events\models\EventLanding;
use open20\amos\news\models\News;
use Yii;

/**
 * This is the base-model class for table "event_landing_news".
 *
 * @property integer $id
 * @property integer $event_landing_id
 * @property integer $news_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\EventLanding $eventLanding
 * @property \open20\amos\events\models\News $news
 */
class  EventLandingNews extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_landing_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_landing_id', 'news_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['event_landing_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventLanding::className(), 'targetAttribute' => ['event_landing_id' => 'id']],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
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
            'news_id' => Yii::t('amosevents', 'Name'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }
}
