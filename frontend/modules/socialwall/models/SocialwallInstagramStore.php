<?php

namespace frontend\modules\socialwall\models;

use open20\amos\core\record\Record;

/**
 * This is the base-model class for table "log_azioni_cron".
 *
 * @property integer $id
 * @property string $json_store
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class SocialwallInstagramStore extends Record {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'socialwall_instagram_store';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['json_store'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('instagram', 'ID'),
            'json_store' => Yii::t('instagram', 'json_store'),
            'created_at' => Yii::t('instagram', 'Created at'),
            'updated_at' => Yii::t('instagram', 'Updated at'),
            'deleted_at' => Yii::t('instagram', 'Deleted at'),
            'created_by' => Yii::t('instagram', 'Created by'),
            'updated_by' => Yii::t('instagram', 'Updated by'),
            'deleted_by' => Yii::t('instagram', 'Deleted by'),
        ];
    }

}
