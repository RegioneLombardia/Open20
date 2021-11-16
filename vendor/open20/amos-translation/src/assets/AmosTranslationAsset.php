<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\translation\assets;

use yii\web\AssetBundle;

/**
 * Class AmosChatAsset
 * @package open20\amos\chat\assets
 */
class AmosTranslationAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/open20/amos-translation/src/assets/web';

    /**
     * @var array
     */
    public $css = [
        'css/style.css',
    ];

    /**
     * @var array
     */
    public $js = [];

    /**
     * @var array
     */
    public $depends = [
        'open20\amos\core\views\assets\AmosCoreAsset',
    ];
}