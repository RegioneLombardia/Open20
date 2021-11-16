<?php

namespace open20\amos\notificationmanager\models\base;

use Yii;

/**
 * This is the base-model class for table "notification_content_language".
 *
 * @property integer $id
 * @property integer $language
 * @property integer $models_classname_id
 * @property integer $record_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\notify\models\ModelsClassname $modelsClassname
 */
class  NotificationContentLanguage extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_content_language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language', 'models_classname_id'], 'required'],
            [['language', 'models_classname_id', 'record_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['models_classname_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsClassname::className(), 'targetAttribute' => ['models_classname_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosnotify', 'ID'),
            'language' => Yii::t('amosnotify', 'Lingua'),
            'models_classname_id' => Yii::t('amosnotify', 'Network'),
            'record_id' => Yii::t('amosnotify', 'Record id'),
            'created_at' => Yii::t('amosnotify', 'Created at'),
            'updated_at' => Yii::t('amosnotify', 'Updated at'),
            'deleted_at' => Yii::t('amosnotify', 'Deleted at'),
            'created_by' => Yii::t('amosnotify', 'Created by'),
            'updated_by' => Yii::t('amosnotify', 'Updated at'),
            'deleted_by' => Yii::t('amosnotify', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsClassname()
    {
        return $this->hasOne(\open20\amos\notify\models\ModelsClassname::className(), ['id' => 'models_classname_id']);
    }
}
