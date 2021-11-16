<?php

/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;
use kartik\base\BaseAssetBundle;

/**
 * Asset bundle for IcoFont icon set. Uses client assets (CSS, images, and fonts) from IcoFont repository.
 *
 * @since 1.4.7
 */

class IcoFontAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib/icofont');
        $this->setupAssets('css', ['css/icofont']);
        parent::init();
    }
}