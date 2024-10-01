<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-layout
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

class LoadingSpinnerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-layout/src/assets/resources/loading';

    public $css        = [
        'less/loading.less'
    ];

    public $js         = [
    ];

    public $depends    = [
    ];

}