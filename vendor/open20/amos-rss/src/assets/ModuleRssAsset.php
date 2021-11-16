<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-rss
 * @category   CategoryName
 */

namespace amos\rss\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;


/**
 * Class ModuleRssAsset
 * @package amos\rss\assets
 */
class ModuleRssAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-rss/src/assets/web';

    public $css = [
        'less/rss.less'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
    ];


    /**
     * @inheritdoc
     */
    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/rss_fullsize.less'];
        }

        if (!empty($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}