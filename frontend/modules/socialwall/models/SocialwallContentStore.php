<?php

namespace frontend\modules\socialwall\models;

use open20\amos\core\record\Record;

/**
 * This is the base-model class for table "log_azioni_cron".
 *
 * @property integer $id
 * @property string $module_id
 * @property integer $module_record_id
 * @property string $social
 * @property string $json_data_content
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class SocialwallContentStore extends Record {

    const SOCIAL_INSTAGRAM = "instagram";
    const SOCIAL_TWITTER = "twitter";

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'socialwall_content_store';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_by', 'updated_by', 'deleted_by', 'module_record_id'], 'integer'],
            [['module_id', 'social', 'json_data_content'], 'string'],
            [['module_id', 'social', 'json_data_content', 'module_record_id'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('socialwall', 'ID'),
            'module_id' => Yii::t('socialwall', 'Module id'),
            'module_record_id' => Yii::t('socialwall', 'Record id for module'),
            'social' => Yii::t('socialwall', 'Social'),
            'json_data_content' => Yii::t('socialwall', 'Json social content data'),
            'created_at' => Yii::t('socialwall', 'Created at'),
            'updated_at' => Yii::t('socialwall', 'Updated at'),
            'deleted_at' => Yii::t('socialwall', 'Deleted at'),
            'created_by' => Yii::t('socialwall', 'Created by'),
            'updated_by' => Yii::t('socialwall', 'Updated by'),
            'deleted_by' => Yii::t('socialwall', 'Deleted by'),
        ];
    }

}
