<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-select2
 * @version 2.2.5
 */

namespace kartik\select2;

/**
 * Asset bundle for the Krajee theme for the [[Select2]] widget.
 *
 * @since 1.0
 */
class ThemeKrajeeBs4Asset extends ThemeAsset
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initTheme();
        $this->setupAssets('css', ['css/select2-krajee-bs4']);
        parent::init();
    }
}
