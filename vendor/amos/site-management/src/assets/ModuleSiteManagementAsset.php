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

class ModuleSiteManagementAsset extends AssetBundle
{
    public $sourcePath = '@vendor/amos/site-management/src/assets/web';

    public $css = [
        'less/site-management.less',
    ];
    public $js = [
    ];
    public $depends = [
    ];
    
}