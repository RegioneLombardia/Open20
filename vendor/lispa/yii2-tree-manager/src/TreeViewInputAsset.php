<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

/**
 * @package   yii2-tree-manager
 * @version   1.0.8
 */

namespace kartik\tree;

use kartik\base\AssetBundle;

/**
 * Asset bundle for TreeViewInput widget.
 *
 * @since  1.0
 */
class TreeViewInputAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'kartik\tree\TreeViewAsset'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/kv-tree-input']);
        $this->setupAssets('js', ['js/kv-tree-input']);
        parent::init();
    }
}
