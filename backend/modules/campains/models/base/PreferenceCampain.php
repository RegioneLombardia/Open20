<?php

namespace backend\modules\campains\models\base;

use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\models\PreferenceCampainTagMm;
use backend\modules\campains\Module;
use backend\modules\operators\models\PreferenceStructure;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\record\ContentModel;
use open20\amos\core\user\User;
use Yii;

/**
 * This is the base-model class for table "preference_campain".
 *
 * @property integer $id
 * @property string $titolo
 * @property string $status
 * @property string $search_params
 * @property string $created_at
 * @property string $updated_atF
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $pc_structure_id
 *
 * @property PreferenceCampainChannelMm[] $preferenceCampainChannelMms
 * @property PreferenceCampainTagMm[] $preferenceCampainTagMms
 */

class PreferenceCampain extends ContentModel {
    /**
     * @var AmosEvents $eventsModule
     */

    public $isSearch = false;

    public $profili;
    public $channel;
    public $tag_cittadino, $tag_impresa, $tag_ente_operatore;
    public $include, $exclude, $email_test;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'preference_campain';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'updated_at', 'deleted_at', 'preferencesTags', 'search_params'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'pc_structure_id'], 'integer'],
            [['titolo', 'status'], 'string', 'max' => 255],
            [['titolo', 'pc_structure_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('campains', 'ID'),
            'titolo' => Module::t('campains', 'Titolo'),
            'pc_structure_id' => Module::t('campains', 'Struttura'),
            'status' => Module::t('campains', 'Status'),
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
    public function getPreferenceCampainChannelMms() {
        return $this->hasMany(PreferenceCampainChannelMm::className(), ['preference_campain_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceCampainTagMms() {
        return $this->hasMany(PreferenceCampainTagMm::className(), ['preference_campain_id' => 'id']);
    }
    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [

        ];
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getDescription($truncate)
    {
        $ret = $this->description;

        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
        }
        return $ret;
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedUserProfile() {
        return  $this->hasOne(UserProfile::className(), ['user_id' => 'created_by']);
    }

         /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructure() {
        return  $this->hasOne(PreferenceStructure::className(), ['id' => 'pc_structure_id']);
    }


}
