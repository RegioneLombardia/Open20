<?php
/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;

use kartik\base\BaseAssetBundle;

/**
 * Asset bundle for Octicons icon set. Uses client assets (CSS, images, and fonts) from Github Icons repository.
 * 
 *
 * @since 1.0
 */

class OcticonsAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib/octicons');
        $this->setupAssets('css', ['octicons']);
        parent::init();
    }

}