<?php

/**
 * @package   yii2-grid
 * @version   3.2.9
 */

namespace kartik\grid;

use \kartik\base\AssetBundle;

/**
 * Asset bundle for resizable columns functionality for the [[GridView]] widget.
 *
 * @since 1.0
 */
class GridResizeColumnsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge($this->depends, ['kartik\grid\GridViewAsset']);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/jquery.resizableColumns']);
        $this->setupAssets('css', ['css/jquery.resizableColumns']);
        parent::init();
    }
}
