<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo
 * @category   CategoryName
 */

namespace open20\amos\seo\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use open20\amos\seo\AmosSeo;
//use open20\amos\seo\models\SeoMetadati;
use open20\amos\seo\models\SeoData;


class SeoWidget extends Widget
{

    public $contentModel;

    public function run()
    {        
        $seoData = SeoData::findOne([
                    'classname' => $this->contentModel->className(),
                    'content_id' => $this->contentModel->id
        ]);
        if (is_null($seoData)) {
            $seoData = new SeoData();
        } else {
           $seoData->prepareSeoData();
        }
        //pr($seoData->toArray(), 'SeoWidget - $seoData SONO RIMASTA QUI');//exit;
        //print 'Pikkioooo. ...';exit;
        return $this->render('seo-data', [
            'modelClass' => AmosSeo::getModel(),
            'model' => $seoData,
            'contentModel' => $this->contentModel
        ]);
    }

}