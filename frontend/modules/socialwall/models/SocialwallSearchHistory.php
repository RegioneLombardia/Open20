<?php

namespace frontend\modules\socialwall\models;

use open20\amos\core\record\Record;

/**
 * This is the base-model class for table "log_azioni_cron".
 *
 * @property integer $id
 * @property string $module_id
 * @property integer $module_record_id
 * @property string $search_history
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class SocialwallSearchHistory extends Record {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'socialwall_search_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_by', 'updated_by', 'deleted_by', 'module_record_id'], 'integer'],
            [['search_history', 'module_id'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => \Yii::t('instagram', 'ID'),
            'module_id' => \Yii::t('instagram', 'Module id'),
            'module_record_id' => \Yii::t('instagram', 'Module Record id'),
            'search_history' => \Yii::t('instagram', 'Search history as json'),
            'created_at' => \Yii::t('instagram', 'Created at'),
            'updated_at' => \Yii::t('instagram', 'Updated at'),
            'deleted_at' => \Yii::t('instagram', 'Deleted at'),
            'created_by' => \Yii::t('instagram', 'Created by'),
            'updated_by' => \Yii::t('instagram', 'Updated by'),
            'deleted_by' => \Yii::t('instagram', 'Deleted by'),
        ];
    }

}
