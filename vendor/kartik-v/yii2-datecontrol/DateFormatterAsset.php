<?php

/**
 * @package   yii2-datecontrol
 * @version   1.9.5
 */

namespace kartik\datecontrol;

use Yii;
use kartik\base\AssetBundle;

/**
 * Asset bundle for the [Krajee PHP Date Formatter](http://plugins.krajee.com/php-date-formatter) extension.
 *
 * @since 1.0
 */
class DateFormatterAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath('@vendor/kartik-v/php-date-formatter');
        $this->setupAssets('js', ['js/php-date-formatter']);
        parent::init();
    }
}