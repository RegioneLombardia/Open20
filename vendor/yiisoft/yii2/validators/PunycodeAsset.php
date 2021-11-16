<?php
/**
 */

namespace yii\validators;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files needed for the [[EmailValidator]]s client validation.
 *
 * @since 2.0
 */
class PunycodeAsset extends AssetBundle
{
    public $sourcePath = '@bower/punycode';
    public $js = [
        'punycode.js',
    ];
}
