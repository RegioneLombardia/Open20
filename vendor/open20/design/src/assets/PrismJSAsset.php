<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\design\layout
 * @category   CategoryName
 */

namespace open20\design\assets;

use yii\web\AssetBundle;

/**
 * Class PrismJSAsset
 * @package open20\design\assets
 */

class PrismJSAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/design/src/assets/resources/bootstrapitaliacustom';

    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/components/prism-core.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/plugins/autoloader/prism-autoloader.min.js',
    ];

    public $css = [
      'https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/themes/prism-okaidia.min.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
