<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\assets;

use open20\amos\core\widget\WidgetAbstract;
use yii\web\AssetBundle;

class ModuleTagFormAjaxAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-tag/src/assets/web';

    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
    ];

    /*
    //force reset cache asset (devel ONLY)
    public $publishOptions = [
        'forceCopy'=>true,
    ];
    */

    public function init()
    {

        if (!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x') {

        } else {
            if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
                $this->css = ['less/tag_fullsize.less'];
            }else {
                $this->css = ['less/tag.less'];
            }
        }


        parent::init();
    }
}
