<?php

namespace backend\modules\campains\models\base;

use backend\modules\campains\Module;
use open20\amos\core\record\Record;

/**
 * This is the base-model class for table "preference_campain_mailup_import".
 *
 * @property integer $id
 * @property integer $preference_campain_channel_mm_id
 * @property integer $mailup_import_id
 * @property string $response
 * @property boolean $active
 *
 * @property \backend\modules\campains\models\PreferenceCampainChannelMm $preferenceCampainChannelMm
 */

class PreferenceCampainMailupImport extends Record {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'preference_campain_mailup_import';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'mailup_import_id', 'preference_campain_channel_mm_id'], 'integer'],
            [['response'], 'string'],
            [['active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('campains', 'ID'),
            'preference_campain_channel_mm_id' => Module::t('campains', 'FK preference_campain_channel_mm'),
            'mailup_import_id' => Module::t('campains', 'FK to mailup'),
            'response' => Module::t('campains', 'response'),
            'active' => Module::t('campains', 'active'),
            'created_at' => Module::t('campains', 'Created at'),
            'updated_at' => Module::t('campains', 'Updated at'),
            'deleted_at' => Module::t('campains', 'Deleted at'),
            'created_by' => Module::t('campains', 'Created by'),
            'updated_by' => Module::t('campains', 'Updated by'),
            'deleted_by' => Module::t('campains', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceCampainChannelMm() {
        return $this->hasOne(\backend\modules\campains\models\PreferenceCampainChannelMm::className(), ['id' => 'preference_campain_channel_mm_id']);
    }

}
