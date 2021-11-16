<?php

/**
 * @package   yii2-grid
 * @version   3.2.9
 */

namespace kartik\grid;

use kartik\base\AssetBundle;

/**
 * Asset bundle for [[CheckboxColumn]] functionality of the [[GridView]] widget.
 *
 * @since 1.0
 */
class CheckboxColumnAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/kv-grid-checkbox']);
        parent::init();
    }
}
