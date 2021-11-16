<?php

namespace backend\modules\campains\models\base;

use backend\modules\campains\Module;
use Yii;
use preference\userprofile\models\Tag;

/**
 * This is the base-model class for table "preference_campain_tag_mm".
 *
 * @property integer $id
 * @property integer $preference_campain_id
 * @property integer $tag_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \backend\modules\campains\models\PreferenceCampain $preferenceCampain
 * @property \backend\modules\campains\models\Tag $tag
 */
class PreferenceCampainTagMm extends \open20\amos\core\record\Record {

    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'preference_campain_tag_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['preference_campain_id', 'tag_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['preference_campain_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceCampain::className(), 'targetAttribute' => ['preference_campain_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('campains', 'ID'),
            'preference_campain_id' => Module::t('campains', 'Preference campain'),
            'tag_id' => Module::t('campains', 'Tag'),
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
    public function getPreferenceCampain() {
        return $this->hasOne(\backend\modules\campains\models\PreferenceCampain::className(), ['id' => 'preference_campain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag() {
        return $this->hasOne(\preference\userprofile\models\Tag::className(), ['id' => 'tag_id']);
    }

}
