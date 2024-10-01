<?php
/**
 */

namespace ymaker\social\share\assets;

use yii\web\AssetBundle;

/**
 * Asset for social icons font.
 *
 * @since 1.0
 */
class SocialIconsAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@vendor/yiimaker/yii2-social-share/src/assets/src';
    /**
     * {@inheritdoc}
     */
    public $css = [YII_ENV_PROD ? 'css/style.min.css' : 'css/style.css'];
}
