<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\news\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

class ModuleNewsAsset extends AssetBundle
{
    /**
     * @var type
     */
    public $sourcePath = '@vendor/open20/amos-news/src/assets/web';

    /**
     * @var type
     */
    public $css = [
        'less/news.less',
    ];
    
    /**
     * @var type
     */
    public $js = [
        'js/news-module.js',
        'js/news.js'
    ];
    
    /**
     * @var type
     */
    public $depends = [];

    /**
     * 
     */
    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if (
            !empty(\Yii::$app->params['dashboardEngine'])
            && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS
        ) {
            $this->css = ['less/news_fullsize.less','less/news_design_bi.less'];
        }

        if (!empty($moduleL)) {
            $this->depends[] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends[] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }

        parent::init();
    }
}