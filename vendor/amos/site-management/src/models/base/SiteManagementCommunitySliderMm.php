<?php

namespace amos\sitemanagement\models\base;

use open20\amos\community\models\Community;
use Yii;

/**
 * This is the base-model class for table "site_management_community_slider_mm".
 *
 * @property integer $id
 * @property integer $community_id
 * @property integer $site_management_slider_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 */
class SiteManagementCommunitySliderMm extends \open20\amos\core\record\Record
{
    const TYPE_IMAGES = 'images';
    const TYPE_VIDEOS = 'videos';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_community_slider_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community_id', 'site_management_slider_id'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'community_id', 'site_management_slider_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'community_id' => Yii::t('amossitemanagement', 'Community'),
            'site_management_slider_id' => Yii::t('amossitemanagement', 'Slider'),
            'type' => Yii::t('amossitemanagement', 'Type of slider'),
            'created_at' => Yii::t('amossitemanagement', 'Created at'),
            'updated_at' => Yii::t('amossitemanagement', 'Updated at'),
            'deleted_at' => Yii::t('amossitemanagement', 'Deleted at'),
            'created_by' => Yii::t('amossitemanagement', 'Created by'),
            'updated_by' => Yii::t('amossitemanagement', 'Updated at'),
            'deleted_by' => Yii::t('amossitemanagement', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementSlider()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementSlider::className(), ['id' => 'site_management_slider_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity()
    {
        return $this->hasOne(Community::className(), ['community_id' => 'id']);
    }
}
