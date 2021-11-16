<?php

/**
 * @package   yii2-grid
 * @version   3.2.9
 */

namespace kartik\grid;

use kartik\base\AssetBundle;

/**
 * Asset bundle for perfect scrollbar functionality for the [[GridView]] widget.
 *
 * @since 1.0
 */
class GridPerfectScrollbarAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/perfect-scrollbar']);
        $this->setupAssets('js', ['js/perfect-scrollbar']);
        parent::init();
    }
}
