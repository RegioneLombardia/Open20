<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\elasticsearch
 * @category   CategoryName
 */

namespace open20\elasticsearch\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

class ElasticSearchAsset extends AssetBundle
{
    /**
     * @var type
     */
    public $sourcePath = '@vendor/open20/amos-elasticsearch/src/assets/web';

    /**
     * @var type
     */
    public $css = [
    ];
    
    /**
     * @var type
     */
    public $js = [
        
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
            $this->css = ['less/elastic-search-bi.less'];
        }

        if (!empty($moduleL)) {
            $this->depends[] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends[] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }

        parent::init();
    }
}