<?php

namespace open20\socialwall\models;

use open20\amos\core\record\Record;
use Yii;
use open20\socialwall\Module;

/**
 * This is the base-model class for table "socialwall_instagram_store".
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
            'id' => Module::t('socialwall', 'ID'),
            'json_store' => Module::t('socialwall', 'json_store'),
            'created_at' => Module::t('socialwall', 'Created at'),
            'updated_at' => Module::t('socialwall', 'Updated at'),
            'deleted_at' => Module::t('socialwall', 'Deleted at'),
            'created_by' => Module::t('socialwall', 'Created by'),
            'updated_by' => Module::t('socialwall', 'Updated by'),
            'deleted_by' => Module::t('socialwall', 'Deleted by'),
        ];
    }

}
