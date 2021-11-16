<?php

namespace open20\amos\events\models\base;

use Yii;

/**
 * This is the base-model class for table "event_container".
 *
 * @property integer $id
 * @property integer $event_group_referent_id
 * @property string $title
 * @property string $description
 * @property string $end_date
 * @property string $begin_date
 * @property string $publish_on_prl
 * @property integer $luya_template_id
 * @property integer $luya_page_id
 * @property string $url
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  EventContainer extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    public $eventsConnected;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_container';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'],'required'],
            [['publish_on_prl', 'event_group_referent_id', 'luya_template_id', 'luya_page_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['end_date','begin_date', 'eventsConnected','created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['slug', 'url','title', 'description'], 'string', 'max' => 255],
            ['mainImage', 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'title' => Yii::t('amosevents', 'Title'),
            'description' => Yii::t('amosevents', 'Description'),
            'luya_template_id' => Yii::t('amosevents', 'template'),
            'luya_page_id' => Yii::t('amosevents', 'Page'),
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
    public function getEvents()
    {
        return $this->hasMany(\open20\amos\events\models\Event::className(), ['event_container_id' => 'id'])->orderBy('event_container_order');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventGroupReferent()
    {
        return $this->hasOne(\open20\amos\events\models\EventGroupReferent::className(), ['id' => 'event_group_referent_id']);
    }
}
