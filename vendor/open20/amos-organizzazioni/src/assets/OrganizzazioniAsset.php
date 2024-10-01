<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

/**
 * 
 */
class OrganizzazioniAsset extends AssetBundle
{
    /**
     * 
     * @var type
     */
    public $sourcePath = '@vendor/open20/amos-organizzazioni/src/assets/web';
    
    /**
     * 
     * @var type
     */
    public $js = [];
    
    /**
     * 
     * @var type
     */
    public $css = [
        'less/organizzazioni.less',
    ];
    
    /**
     * 
     * @var type
     */
    public $depends = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if (
            !empty(\Yii::$app->params['dashboardEngine'])
            && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS
        ) {
            $this->css = ['less/organizzazioniFullsize.less'];
        }

        if (!empty($moduleL)) {
            $this->depends[] = 'open20\amos\layout\assets\BaseAsset';
        }

        parent::init();
    }

}