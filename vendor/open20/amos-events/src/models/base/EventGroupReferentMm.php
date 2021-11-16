<?php

namespace open20\amos\events\models\base;

use Yii;

/**
 * This is the base-model class for table "event_group_referent_mm".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $event_group_referent_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\EventGroupReferent $eventGroupReferent
 * @property \open20\amos\events\models\User $user
 */
class EventGroupReferentMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_group_referent_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'event_group_referent_id', 'created_by', 'updated_by', 'deleted_by', 'exclude_from_query'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['event_group_referent_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\events\models\EventGroupReferent::className(),
                'targetAttribute' => ['event_group_referent_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'user_id' => Yii::t('amosevents', 'Utente'),
            'event_group_referent_id' => Yii::t('amosevents', 'Descrizione'),
            'exclude_from_query' => Yii::t('amosevents', 'Utente amministratore di gruppo'),
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
    public function getEventGroupReferent()
    {
        return $this->hasOne(\open20\amos\events\models\EventGroupReferent::className(),
                ['id' => 'event_group_referent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    { 
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }
}