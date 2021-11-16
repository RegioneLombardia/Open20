<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo\behaviors
 * @category   CategoryName
 */

namespace open20\amos\seo\behaviors;

use open20\amos\seo\models\SeoData;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\helpers\ArrayHelper;

/**
 * Class SeoBehaviors
 * @package open20\amos\seo\behaviors
 */
class SeoContentBehavior extends AttributeBehavior {

    private $contentSeoData = null;   // non chiamarla direttamente ma usare la get

    /**
     * @var string the title field
     */
    public $titleAttribute = 'titolo';

    /**
     * @var string the title description
     */
    public $descriptionAttribute = 'descrizione_breve';

    /**
     * @var string the title field
     */
    public $imageAttribute = 'image';

    /**
     * @var string the title field
     */
    public $schema;

    /**
     * @var string the title field
     */
    public $defaultOgType = 'website';

    public function getPrettyUrl() {

        $seoData = $this->getContentSeoData();

        return $seoData->pretty_url;
    }

    public function getMetaTitle() {

        $seoData = $this->getContentSeoData();
        $meta_title = ($seoData->meta_title) ? $seoData->meta_title : ArrayHelper::getValue($this->owner, $this->titleAttribute);

        return $meta_title;
    }

    public function getMetaDescription() {

        $seoData = $this->getContentSeoData();
        $meta_description = ($seoData->meta_description) ? $seoData->meta_description : ArrayHelper::getValue($this->owner, $this->descriptionAttribute);

        return $meta_description;
    }

    public function getMetaKeywords() {

        $seoData = $this->getContentSeoData();

        return $seoData->meta_keywords;
    }

    public function getOgTitle() {

        $seoData = $this->getContentSeoData();
        $og_title = ($seoData->og_title) ? $seoData->og_title : $this->getMetaTitle();

        return $og_title;
    }

    public function getOgImageUrl() {

        $seoData = $this->getContentSeoData();
        if (!is_null($seoData->ogImage)) {
            $og_image_url = \yii\helpers\Url::base(true).$seoData->ogImage->getWebUrl('square_medium', false, false);
        } else {
            if (is_null($this->imageAttribute)) {
                return null;
            } else {
                $contentImage = ArrayHelper::getValue($this->owner, $this->imageAttribute);
                $og_image_url = (!is_null($contentImage)) ? \yii\helpers\Url::base(true).$contentImage->getWebUrl('square_medium', false, false) : null;
            }
        }
        
        return $og_image_url;
    }

    public function getOgDescription() {

        $seoData = $this->getContentSeoData();
        $og_description = ($seoData->og_description) ? $seoData->og_description : $this->getMetaDescription();

        return $og_description;
    }

    public function getOgType() {

        $seoData = $this->getContentSeoData();

        $og_type = ($seoData->og_type) ? $seoData->og_type : $this->defaultOgType;

        return $og_type;
    }

    public function getMetaRobots() {

        $seoData = $this->getContentSeoData();
        $theDate = $this->getUnavailableAfterDate();
        $meta_robots = preg_replace('/unavailable_after/', 'unavailable_after: '.$theDate, $seoData->meta_robots);

        return $meta_robots;
    }


    public function getMetaGooglebot() {

        $seoData = $this->getContentSeoData();
        $theDate = $this->getUnavailableAfterDate();
        $meta_googlebot = preg_replace('/unavailable_after/', 'unavailable_after: '.$theDate, $seoData->meta_googlebot);

        return $meta_googlebot;

    }


    public function getUnavailableAfterDate($formatted = 'RFC-850') {

        $seoData = $this->getContentSeoData();

        $unavailable_after_date = $seoData->unavailable_after_date;
        if ($formatted == 'RFC-850') {
            $oldLocale = Yii::$app->formatter->locale;
            Yii::$app->formatter->locale = 'en-US';
            $theDate = Yii::$app->formatter->asDate($unavailable_after_date, 'php:d M Y H:i:s T');
            Yii::$app->formatter->locale = $oldLocale;
        }

        return $theDate;
    }

    public function getContentSeoData() {

        if (is_null($this->contentSeoData)) {
            $this->setContentSeoData();
        }
        return $this->contentSeoData;
    }

    public function setContentSeoData() {

        $this->contentSeoData = SeoData::findOne([
                    'classname' => $this->owner->className(),
                    'content_id' => $this->owner->id
        ]);
        if (is_null($this->contentSeoData)) {
            $this->contentSeoData = new SeoData();
        }
    }

}
