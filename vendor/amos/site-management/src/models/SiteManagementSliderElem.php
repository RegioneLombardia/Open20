<?php

namespace amos\sitemanagement\models;

use amos\sitemanagement\Module;
use open20\amos\attachments\behaviors\FileBehavior;
use function GuzzleHttp\Psr7\_caseless_remove;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_management_slider_elem".
 */
class SiteManagementSliderElem extends \amos\sitemanagement\models\base\SiteManagementSliderElem
{
    const TYPE_IMG = 1;
    const TYPE_VIDEO = 2;

    const TEXT_POSITION_TOP_LEFT = 1;
    const TEXT_POSITION_TOP_CENTER = 2;
    const TEXT_POSITION_TOP_RIGHT = 3;
    const TEXT_POSITION_CENTER_LEFT = 4;
    const TEXT_POSITION_CENTER = 5;
    const TEXT_POSITION_CENTER_RIGHT = 6;
    const TEXT_POSITION_BOTTOM_LEFT = 7;
    const TEXT_POSITION_BOTTOM_CENTER = 8;
    const TEXT_POSITION_BOTTOM_RIGHT = 9;



    public $fileImage;
    public $typeOfvideo;

    /**
     * Adding the file behavior
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ]
        ]);
    }
    
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['fileImage'], 'file'],
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'slider_id',
                'label' => $labels['slider_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'type',
                'label' => $labels['type'],
                'type' => 'integer'
            ],
            [
                'slug' => 'url_video',
                'label' => $labels['url_video'],
                'type' => 'string'
            ],
            [
                'slug' => 'order',
                'label' => $labels['order'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return File[]
     */
    public function getFileImage()
    {
        if (empty($this->fileImage)) {
            $query = $this->hasOneFile('fileImage');
            $this->fileImage = $query->one();
        }
        return $this->fileImage;
    }

    /**
     * @return string
     */
    public function getLabelType(){
        switch($this->type){
            case self::TYPE_IMG:
                return Module::t('amossitemanagement','Image');
                break;
            case self::TYPE_VIDEO:
                return Module::t('amossitemanagement','Video');
                break;

        }
    }

    /**
     * @param $url
     * @return mixed
     */
    public static function getUrlEmbedVideoStatic($url){
        $elem = new SiteManagementSliderElem();
        return $elem->getUrlEmbeddedVideo($url);
    }

    /**
     * @param null $urlVideo
     * @return null|string|string[]
     */
    public function getUrlEmbeddedVideo($urlVideo = null){
        $url = $this->url_video;
        if(!empty($urlVideo)){
            $url = $urlVideo;
        }
        $url = preg_replace('/&t=([0-9]*[msh])+/', '', $url);

        $baseUrl = 'https://www.youtube.com/embed/';
        if(!empty($url)){
            if(strpos($url, 'embedded')){
                return $url;
            }
            if(strpos($url, 'watch')){
                $exploded = explode('watch?v=', $url);
                if(count($exploded) == 2) {
                    $url = $baseUrl . $exploded[1];
                }
            }
            else {
                $exploded = explode('/', $url);
                $url = $baseUrl. end($exploded);
            }

            if(strpos($url, '?')) {
                $url .= '&enablejsapi=1&html5=1';
            } else {
                $url .= '?enablejsapi=1&html5=1';
            }
        }
        return $url;
    }

    /**
     * @return string
     */
    public function getTexPositionClasses(){
        $css = '';
        switch ($this->text_position){
            case self::TEXT_POSITION_TOP_LEFT :
                $css = "carousel-caption-left carousel-caption-top";
                break;
            case self::TEXT_POSITION_TOP_CENTER :
                $css = "carousel-caption-top carousel-caption-horizontal-center";
                break;
            case self::TEXT_POSITION_TOP_RIGHT :
                $css = "carousel-caption-top carousel-caption-right";
                break;

            case self::TEXT_POSITION_CENTER_LEFT :
                $css = "carousel-caption-left carousel-caption-vertical-center";
                break;
            case self::TEXT_POSITION_CENTER :
                $css = "carousel-caption-vertical-center carousel-caption-horizontal-center";
                break;
            case self::TEXT_POSITION_CENTER_RIGHT :
                $css = "carousel-caption-right carousel-caption-vertical-center";
                break;

            case self::TEXT_POSITION_BOTTOM_LEFT :
                $css = "carousel-caption-left carousel-caption-bottom";
                break;
            case self::TEXT_POSITION_BOTTOM_CENTER :
                $css = "carousel-caption-bottom carousel-caption-horizontal-center";
                break;
            case self::TEXT_POSITION_BOTTOM_RIGHT :
                $css = "carousel-caption-right carousel-caption-bottom";
                break;
        }
        return $css;
    }


    /**
     * @return string
     */
    public static function getTextPositionLabel($text_position){
        $label = '';
        switch ($text_position){
            case self::TEXT_POSITION_TOP_LEFT :
                $label = Module::t('amossitemanagement', "Top left");
                break;
            case self::TEXT_POSITION_TOP_CENTER :
                $label = Module::t('amossitemanagement', "Top center");
                break;
            case self::TEXT_POSITION_TOP_RIGHT :
                $label = Module::t('amossitemanagement', "Top right");
                break;

            case self::TEXT_POSITION_CENTER_LEFT :
                $label = Module::t('amossitemanagement', "Center left");
                break;
            case self::TEXT_POSITION_CENTER :
                $label = Module::t('amossitemanagement', "Center");
                break;
            case self::TEXT_POSITION_CENTER_RIGHT :
                $label = Module::t('amossitemanagement', "Center right");
                break;

            case self::TEXT_POSITION_BOTTOM_LEFT :
                $label = Module::t('amossitemanagement', "Bottom left");
                break;
            case self::TEXT_POSITION_BOTTOM_CENTER :
                $label = Module::t('amossitemanagement', "Bottom center");
                break;
            case self::TEXT_POSITION_BOTTOM_RIGHT :
                $label = Module::t('amossitemanagement', "Bottom right");
                break;
        }
        return $label;
    }

    /**
     * @return array
     */
    public function getAllTextPositionLabel(){
        return [
            self::TEXT_POSITION_TOP_LEFT => SiteManagementSliderElem::getTextPositionLabel(self::TEXT_POSITION_TOP_LEFT),
            self::TEXT_POSITION_TOP_CENTER => SiteManagementSliderElem::getTextPositionLabel(self::TEXT_POSITION_TOP_CENTER),
            self::TEXT_POSITION_TOP_RIGHT => SiteManagementSliderElem::getTextPositionLabel(self::TEXT_POSITION_TOP_RIGHT),

            self::TEXT_POSITION_CENTER_LEFT => SiteManagementSliderElem::getTextPositionLabel(self::TEXT_POSITION_CENTER_LEFT),
            self::TEXT_POSITION_CENTER => SiteManagementSliderElem::getTextPositionLabel(self::TEXT_POSITION_CENTER),
            self::TEXT_POSITION_CENTER_RIGHT => SiteManagementSliderElem::getTextPositionLabel(self::TEXT_POSITION_CENTER_RIGHT),

            self::TEXT_POSITION_BOTTOM_LEFT => SiteManagementSliderElem::getTextPositionLabel(self::TEXT_POSITION_BOTTOM_LEFT),
            self::TEXT_POSITION_BOTTOM_CENTER => SiteManagementSliderElem::getTextPositionLabel(self::TEXT_POSITION_BOTTOM_CENTER),
            self::TEXT_POSITION_BOTTOM_RIGHT => SiteManagementSliderElem::getTextPositionLabel(self::TEXT_POSITION_BOTTOM_RIGHT),
        ];
    }
}
