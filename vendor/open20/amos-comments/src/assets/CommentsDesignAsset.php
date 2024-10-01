<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\assets
 * @category   CategoryName
 */

namespace open20\amos\comments\assets; 

use yii\web\AssetBundle;

/**
 * Class CommentsDesignAsset
 * @package open20\amos\comments\assets
 */
class CommentsDesignAsset extends AssetBundle
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
        'js/comments_design.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}