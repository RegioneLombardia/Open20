<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement
 * @category   CategoryName
 */

namespace amos\sitemanagement\assets;

use yii\web\AssetBundle;

class ModuleSiteManagementInterfacciaAsset extends AssetBundle
{
    public $sourcePath = '@vendor/amos/site-management/src/assets/web';

    public $css = [
        'less/widget-sitemanagement-design-bi.less',
    ];
    public $js = [
    ];
    public $depends = [
    ];
    
}