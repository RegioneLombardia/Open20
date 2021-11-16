<?php

namespace backend\modules\campains\models\base;

use backend\modules\campains\Module;
use Yii;

/**
 * This is the base-model class for table "preference_campain_section".
 *
 * @property integer $id
 * @property string $title
 * @property string $color
 * @property integer $preference_campain_channel_mm_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \backend\modules\campains\models\PreferenceCampainChannelMm $preferenceCampainChannelMm
 */
class PreferenceCampainSection extends \open20\amos\core\record\Record {

    public $isSearch = false;

    public $preference_campain_id;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'preference_campain_section';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['preference_campain_channel_mm_id', 'created_by', 'updated_by', 'deleted_by', 'order'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'color'], 'string', 'max' => 255],
            [['color'], 'required'],
            [['preference_campain_channel_mm_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceCampainChannelMm::className(), 'targetAttribute' => ['preference_campain_channel_mm_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('campains', 'ID'),
            'title' => Module::t('campains', 'Titolo'),
            'color' => Module::t('campains', 'Color'),
            'preference_campain_channel_mm_id' => Module::t('campains', 'Preference campain channel mm'),
            'order' => Module::t('campains', 'Ordine'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceCampainContainers() {
        return $this->hasMany(\backend\modules\campains\models\PreferenceCampainContainer::className(), ['preference_campain_section_id' => 'id']);
    }


}
