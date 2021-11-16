<?php

/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;

use kartik\base\BaseAssetBundle;

/**
 * Asset bundle for Web Hosting Hub Glyphs. Uses client assets (CSS, images, and fonts) from WHHG repository.
 *
 *
 * @since 1.0
 */
class WhhgAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib/whhg');
        $this->setupAssets('css', ['css/whhg']);
        parent::init();
    }
}