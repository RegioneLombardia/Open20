<?php

/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;

use kartik\base\BaseAssetBundle;

/**
 * Asset bundle for JUI theme set. Uses client assets (CSS, images, and fonts) from Jquery UI Icons repository.
 * 
 *
 * @since 1.0
 */
class JuiAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/jquery-ui';
    /**
     * @inheritdoc
     */
    public $css = [
        'themes/smoothness/jquery-ui.css',
    ];

}