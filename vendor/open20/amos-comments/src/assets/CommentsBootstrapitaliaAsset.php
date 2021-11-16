<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\assets
 * @category   CategoryName
 */

namespace open20\amos\comments\assets;

use yii\web\AssetBundle;

/**
 * Class CommentsAsset
 * @package open20\amos\comments\assets
 */
class CommentsBootstrapitaliaAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-comments/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/comments_bootstrapitalia.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}