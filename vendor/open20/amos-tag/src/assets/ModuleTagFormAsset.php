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

/**
 * 
 */
class ModuleTagFormAsset extends AssetBundle
{
    /**
     * 
     * @var type
     */
    public $sourcePath = '@vendor/open20/amos-tag/src/assets/web';
    
    /**
     * 
     * @var type
     */
    public $css = [];

    /**
     * 
     * @var type
     */
    public $js = [
        'js/tag-form.js',
    ];

    /**
     * 
     * @var type
     */
    public $depends = [];

    /**
     * 
     */
    public function init()
    {
        if (!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x') {
            
        } else {
            if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
                $this->css = ['less/tag_fullsize.less'];
            } else {
                $this->css = ['less/tag.less'];
            }
        }

        parent::init();
    }

}