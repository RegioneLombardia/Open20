<?php
/**
 * @package yii2-widgets
 * @subpackage yii2-widget-sidenav
 * @version 1.0.1
 */

namespace kartik\sidenav;

/**
 * Asset bundle for SideNav Widget
 *
 * @since 1.0
 */
class SideNavAsset extends \kartik\base\AssetBundle
{
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/sidenav']);
        $this->setupAssets('js', ['js/sidenav']);
        parent::init();
    }
}
