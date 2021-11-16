<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use open20\amos\core\widget\WidgetAbstract;
use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package open20\amos\layout\assets
 */
class BaseAsset extends AssetBundle
{
    public $js = [
        'js/bootstrap-tabdrop.js',
        'js/globals.js',
        'js/device-detect.js',
        'js/tooltip-component.js',
        'js/footer.js',
        'js/header.js',
    ];

    public $css = [
        'less/main.less'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'open20\amos\layout\assets\LajaxAsset',
        'yii\bootstrap\BootstrapAsset',
        'kartik\select2\Select2Asset',
        'open20\amos\layout\assets\IEAssets',
        'open20\amos\layout\assets\JqueryUiTouchPunchImprovedAsset',
        'open20\amos\layout\assets\ConflictJuiBootstrap',
        'open20\amos\layout\assets\TourAsset',
        'open20\amos\layout\assets\IconAsset',
        'open20\amos\layout\assets\FontAsset',
        'open20\amos\layout\assets\DialogAsset',
        'open20\amos\layout\assets\LajaxAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/base';
        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/main_fullsize.less'];
        }
        parent::init();
    }
}
