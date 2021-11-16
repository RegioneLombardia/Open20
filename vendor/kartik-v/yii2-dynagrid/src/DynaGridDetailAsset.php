<?php

/**
 * @package   yii2-dynagrid
 * @version   1.5.1
 */

namespace kartik\dynagrid;

use kartik\base\AssetBundle;

/**
 * Asset bundle for [[DynaGridDetail]] widget
 *
 * @since 1.2.0
 */
class DynaGridDetailAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $depends = ["kartik\\dynagrid\\DynaGridAsset"];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/kv-dynagrid-detail']);
        parent::init();
    }
}