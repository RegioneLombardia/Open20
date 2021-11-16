<?php

namespace backend\modules\campains\models\base;

use backend\modules\campains\Module;
use Yii;

/**
 * This is the base-model class for table "preference_campain".
 *
 * @property integer $id
 * @property integer $mailup_template_id
 * @property integer $mailup_template_preview_id
 * @property string $title
 * @property string $path
 * @property string $section
 * @property string $separator
 * @property string $created_at
 * @property string $updated_atF
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 */

class PreferenceMailupTemplate extends \open20\amos\core\record\Record {

    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'preference_mailup_template';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'updated_at', 'deleted_at', 'preferencesTags'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'mailup_template_id', 'mailup_template_preview_id'], 'integer'],
            [['colors_choice', 'aspect_ratio_choices', 'aspect_ratio'], 'safe'],
            [['title', 'default_link_text', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('campains', 'ID'),
            'title' => Module::t('campains', 'Titolo'),
            'mailup_template_id' => Module::t('campains', 'mailup id'),
            'mailup_template_preview_id' => Module::t('campains', 'mailup preview id'),
            'created_at' => Module::t('campains', 'Created at'),
            'updated_at' => Module::t('campains', 'Updated at'),
            'deleted_at' => Module::t('campains', 'Deleted at'),
            'created_by' => Module::t('campains', 'Created by'),
            'updated_by' => Module::t('campains', 'Updated by'),
            'deleted_by' => Module::t('campains', 'Deleted by'),
        ];
    }

    public function getFsSection()
    {
        return $this->getContent(Yii::getAlias($this->path), 'section.html');
    }

    public function getFsSeparator()
    {
        return $this->getContent(Yii::getAlias($this->path), 'separator.html');
    }

    public function getFsContentCenterImage()
    {
        return $this->getContent(Yii::getAlias($this->path), 'content_center_image.html');
    }

    public function getFsContentLeftImage()
    {
        return $this->getContent(Yii::getAlias($this->path), 'content_left_image.html');
    }

    public function getFsContentRightImage()
    {
        return $this->getContent(Yii::getAlias($this->path), 'content_right_image.html');
    }

    public function getFsContentNoImage()
    {
        return $this->getContent(Yii::getAlias($this->path), 'content_no_image.html');
    }

    public function getFsFacebookButton()
    {
        return $this->getContent(Yii::getAlias($this->path), 'button_facebook.html');
    }

    public function getFsTwitterButton()
    {
        return $this->getContent(Yii::getAlias($this->path), 'button_twitter.html');
    }

    public function getFsLinkedinButton()
    {
        return $this->getContent(Yii::getAlias($this->path), 'button_linkedin.html');
    }

    public function getFsLinkButton()
    {
        return $this->getContent(Yii::getAlias($this->path), 'button_link.html');
    }

    public function getSocialSharing()
    {
        return $this->getContent(Yii::getAlias($this->path), 'social_sharing.html');
    }


    /**
     * Undocumented function
     *
     * @param string $path
     * @param string $fileName
     * @return string
     */
    private function getContent($path, $fileName)
    {
        $content = file_get_contents($path . DIRECTORY_SEPARATOR . $fileName);
        if ($content === false) {
            return '';
        }
        return $content;
    }

}
