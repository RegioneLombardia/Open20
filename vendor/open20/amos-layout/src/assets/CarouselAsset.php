<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-layout
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

class CarouselAsset extends AssetBundle
{

    public $css = [
        'less/carousel.less'
    ];
    public $js = [
    ];
    public $depends = [
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/carousel';

        parent::init();
    }
}