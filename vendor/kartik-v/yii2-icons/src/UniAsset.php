<?php

/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;

use kartik\base\BaseAssetBundle;

/**
 * Asset bundle for Krajee Unicode Icons.
 *
 * @since 1.0
 */
class UniAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib/uni');
        $this->setupAssets('css', ['css/kv-unicode-icons']);
        parent::init();
    }
}