<?php
/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;

/**
 * Asset bundle for Socicon icon set. Uses client assets (CSS, images, and fonts) from Socicon repository.
 *
 *
 * @since 1.0
 */

class SociconAsset extends \kartik\base\AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib/socicon');
        $this->setupAssets('css', ['css/socicon']);
        parent::init();
    }

}