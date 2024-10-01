<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\cms\dashboard
 * @category   CategoryName
 */

namespace open20\cms\dashboard\assets;

use yii\web\AssetBundle;

class ModuleCmsDashboardAsset extends AssetBundle
{
    /**
     * @var type
     */
    public $sourcePath = '@vendor/open20/cms-dashboard/src/assets/web';

    /**
     * @var type
     */
    public $css = [
        'less/cms-dashboard.less',
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
        parent::init();
    }
}