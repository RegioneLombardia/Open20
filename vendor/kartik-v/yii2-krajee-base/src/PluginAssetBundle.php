<?php

/**
 * @package   yii2-krajee-base
 * @version   1.9.9
 */

namespace kartik\base;

/**
 * Base asset bundle for Krajee extensions (including bootstrap plugins)
 *
 */
class PluginAssetBundle extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $bsPluginEnabled = true;
}
