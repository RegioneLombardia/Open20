<?php

namespace amos\sitemanagement\models\base;

use amos\sitemanagement\Module;
use Yii;

/**
 * This is the base-model class for table "site_management_slider_elem".
 *
 * @property integer $id
 * @property integer $slider_id
 * @property string $title
 * @property string $description
 * @property string $link
 * @property integer $type
 * @property string $url_video
 * @property string path_video
 * @property integer text_position
 * @property integer $order
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class SiteManagementSliderElem extends \open20\amos\core\record\Record {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'site_management_slider_elem';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['slider_id', 'text_position', 'type', 'order', 'created_by', 'updated_by', 'deleted_by', 'instagram_video_caption', 'target'], 'integer'],
            [['description', 'link'], 'string'],
            [['instagram_url_video'], 'url'],
            [['slider_id', 'type'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'url_video', 'path_video', 'cta_label', 'category', 'btn_icon', 'btn_label'], 'string', 'max' => 255],
            [['link'], 'url'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('amossitemanagement', 'ID'),
            'slider_id' => Module::t('amossitemanagement', 'Slider'),
            'title' => Module::t('amossitemanagement', 'Title'),
            'description' => Module::t('amossitemanagement', 'Description'),
            'type' => Module::t('amossitemanagement', 'Type'),
            'url_video' => Module::t('amossitemanagement', 'Url Video'),
            'instagram_url_video' => Module::t('amossitemanagement', 'Url Video Instagram'),
            'instagram_video_caption' => Module::t('amossitemanagement', 'Instagram Video Caption'),
            'text_position' => Module::t('amossitemanagement', 'Text position'),
            'order' => Module::t('amossitemanagement', 'Ordinamento'),
            'created_at' => Module::t('amossitemanagement', 'Created at'),
            'updated_at' => Module::t('amossitemanagement', 'Updated at'),
            'deleted_at' => Module::t('amossitemanagement', 'Deleted at'),
            'created_by' => Module::t('amossitemanagement', 'Created by'),
            'updated_by' => Module::t('amossitemanagement', 'Updated at'),
            'deleted_by' => Module::t('amossitemanagement', 'Deleted at'),
            'target' => Module::t('amossitemanagement', 'Apri link in una nuova pagina'),
            'btn_icon' => Module::t('amossitemanagement', 'Icona del BTN'),
            'btn_label' => Module::t('amossitemanagement', 'Testo del BTN'),
            'category' => Module::t('amossitemanagement', 'Categoria'),
            'cta_label' => Module::t('amossitemanagement', 'Testo del link'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlider() {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementSlider::className(), ['id' => 'slider_id']);
    }

}
