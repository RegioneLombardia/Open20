<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-fileinput
 * @version 1.0.6
 */

namespace kartik\file;

use kartik\base\AssetBundle;

/**
 * PiExif Asset bundle for FileInput Widget
 *
 * @since 1.0
 */
class PiExifAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath('@vendor/kartik-v/bootstrap-fileinput');
        $this->setupAssets('js', ['js/plugins/piexif']);
        parent::init();
    }
}
