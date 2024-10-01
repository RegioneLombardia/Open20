<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\assets;

use yii\web\AssetBundle;

/**
 * 
 */
class ModuleTagFormFrontendAsset extends AssetBundle
{
    /**
     * 
     * @var type
     */
    public $sourcePath = '@vendor/open20/amos-tag/src/assets/web';
    
    /**
     * 
     * @var type
     */
    public $js = [
        'js/tag-form.js',
    ];

    /**
     * 
     * @var type
     */
    public $depends = [
    ];

}