<?php

namespace open20\amos\favorites\assets;

use yii\web\AssetBundle;

class FavoritesAsset extends AssetBundle
{

    /**
     * @var
     */
    public $sourcePath = '@vendor/open20/amos-favorites/src/assets/web';

    /**
     * @var
     */
    public $css = [
        'less/favorites.less',
    ];

    /**
     * @var
     */
    public $js = [
    ];

    /**
     * @var
     */
    public $depends = [];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
    }
}