<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace frontend\modules\socialwall\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

class ModuleSocialWallAsset extends AssetBundle
{
    /**
     * @var type
     */
    public $sourcePath = '@frontend/modules/socialwall/assets/web';

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