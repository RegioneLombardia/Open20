<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-typeahead
 * @version 1.0.2
 */

namespace kartik\typeahead;

use kartik\base\AssetBundle;

/**
 * Asset bundle for Typeahead Widget (Basic)
 *
 * @since 1.0
 */
class TypeaheadBasicAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/typeahead', 'css/typeahead-kv']);
        $this->setupAssets('js', ['js/typeahead.jquery', 'js/typeahead-kv']);
        parent::init();
    }
}
