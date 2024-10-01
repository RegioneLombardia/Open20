<?php

/**
 * @package   yii2-krajee-base
 * @version   3.0.5
 */

namespace kartik\base;

/**
 * This is the base asset bundle class for Krajee extensions which enables Krajee's special jQuery plugin handling
 * functionality. It also includes special configurations to automatically enable Bootstrap library Plugins based
 * on your bootstrap library version.
 *
 */
class PluginAssetBundle extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $bsPluginEnabled = true;
}
