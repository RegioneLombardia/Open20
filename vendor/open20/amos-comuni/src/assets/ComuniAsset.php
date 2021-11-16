<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\assets
 * @category   CategoryName
 */

namespace open20\amos\comuni\assets;

use yii\web\AssetBundle;

/**
 * Class CommentsAsset
 * @package open20\amos\comuni\assets
 */
class ComuniAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-comuni/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [

    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/comuni_common_js.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
