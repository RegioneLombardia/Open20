<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\socialwall\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

class ModuleSocialWallAsset extends AssetBundle
{
    /**
     * @var type
     */
    public $sourcePath = '@vendor/open20/social-wall/src/assets/web';

    /**
     * @var type
     */
    public $css = [
        'css/_social_wall-masonry.scss',
    ];
    
    /**
     * @var type
     */
    public $js = [
    ];
    
    /**
     * @var type
     */
    public $depends = [];

}