<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-typeahead
 * @version 1.0.4
 */

namespace kartik\typeahead;

use kartik\base\AssetBundle;

/**
 * Asset bundle for Typeahead advanced widget
 *
 * @since 1.0
 */
class TypeaheadAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/typeahead', 'css/typeahead-kv']);
        $this->setupAssets('js', ['js/typeahead.bundle', 'js/typeahead-kv']);
        parent::init();
    }
}
