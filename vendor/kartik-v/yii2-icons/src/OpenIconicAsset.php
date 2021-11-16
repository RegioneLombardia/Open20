<?php

/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;

use kartik\base\BaseAssetBundle;

/**
 * Asset bundle for Open Iconic icon set. Uses client assets (CSS, images, and fonts) from Open Iconic repository.
 * 
 *
 * @since 1.0
 */
class OpenIconicAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib/openiconic');
        $this->setupAssets('css', ['css/open-iconic-bootstrap']);
        parent::init();
    }
}