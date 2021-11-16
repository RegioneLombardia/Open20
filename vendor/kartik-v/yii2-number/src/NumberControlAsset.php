<?php

/**
 * @package   yii2-number
 * @version   1.0.3
 */

namespace kartik\number;

use kartik\base\AssetBundle;

/**
 * Asset bundle for the [[NumberControl]] widget.
 *
 * @since 1.0
 */
class NumberControlAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge($this->depends, ['yii\widgets\MaskedInputAsset']);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/number']);
        parent::init();
    }
}