<?php

namespace backend\modules\campains\models\base;

use backend\modules\campains\Module;
use Yii;

/**
 * This is the base-model class for table "preference_campain_container_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \backend\modules\campains\models\PreferenceCampainContainer[] $preferenceCampainContainers
 */
class PreferenceCampainContainerType extends \open20\amos\core\record\Record {

    public $isSearch = false;

    const ID_NEWSLETTER = 1;
    const ID_SMS = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'preference_campain_container_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('campains', 'ID'),
            'name' => Module::t('campains', 'Name'),
            'description' => Module::t('campains', 'Description'),
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
    public function getPreferenceCampainContainers() {
        return $this->hasMany(\backend\modules\campains\models\PreferenceCampainContainer::className(), ['preference_campain_container_type_id' => 'id']);
    }

}
