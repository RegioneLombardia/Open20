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

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package open20\amos\layout\assets
 */

class JqueryUiTouchPunchImprovedAsset extends AssetBundle {

    public $sourcePath = '@bower/jquery-ui-touch-punch-improved';
    public $js = [
        'jquery.ui.touch-punch-improved.js',
    ];
    public $depends = [
        'yii\jui\JuiAsset'
    ];

}
