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
 * @modified Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.2.5
 */
class ThemeMaterialAsset extends ThemeAsset
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initTheme();
        $this->setupAssets('css', ['css/select2-material']);
        parent::init();
    }
}
