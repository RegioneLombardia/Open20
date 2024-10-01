<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard\assets;

use yii\web\AssetBundle;

class DashboardFullsizeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-dashboard/src/assets/web';
    public $css        = [
        'less/dashboardFullsize.less'
    ];
    public $js         = [
        'js/dashboard_fullsize.js',
        'js/modal-dashboard.js'
    ];
    public $depends = [
    ];
}