<?php

/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;
use kartik\base\BaseAssetBundle;

/**
 * Asset bundle for FlagIcon icon set. Uses client assets (CSS, images, and fonts) from flag icons repository.
 * 
 *
 * @since 1.4
 */
class FlagIconAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/components/flag-icon-css';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setupAssets('css', ['css/flag-icon']);
        parent::init();
    }
}