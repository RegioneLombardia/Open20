<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "log_azioni_cron".
 *
 * @property integer $id
 * @property string $controller_id
 * @property string $action_id
 * @property integer $start
 * @property integer $end
 * @property integer $durata
 * @property string $messaggio
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class LogAzioniCron extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_azioni_cron';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller_id', 'action_id', 'messaggio'], 'string'],
            [['start', 'end', 'created_by', 'updated_by', 'deleted_by', 'durata'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'controller_id' => Yii::t('app', 'Controller ID'),
            'action_id' => Yii::t('app', 'Action ID'),
            'start' => Yii::t('app', 'Action ID'),
            'end' => Yii::t('app', 'Action ID'),
            'durata' => Yii::t('app', 'Durata in millisecondi'),
            'messaggio' => Yii::t('app', 'Messaggio di log'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }
}
