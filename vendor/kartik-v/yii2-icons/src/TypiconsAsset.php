<?php

/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;

use kartik\base\BaseAssetBundle;

/**
 * Asset bundle for Typicons icon set. Uses client assets (CSS, images, and fonts) from Typicons repository.
 *
 * 
 * @since 1.0
 */
class TypiconsAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib/typicons');
        $this->setupAssets('css', ['css/typicons']);
        parent::init();
    }
}