<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-select2
 * @version 2.2.5
 */

namespace kartik\select2;

/**
 * Asset bundle for the default inbuilt theme for the [[Select2]] widget.
 *
 * @since 1.0
 */
class ThemeDefaultAsset extends ThemeAsset
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initTheme();
        $this->setupAssets('css', ['css/select2-default']);
        parent::init();
    }
}
