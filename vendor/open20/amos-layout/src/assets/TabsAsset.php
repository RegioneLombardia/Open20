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

class TabsAsset extends AssetBundle
{

    public $css = [

    ];
    public $js = [
        'js/tabs.js',
    ];
    public $depends = [
        'open20\amos\layout\assets\BaseAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/tab';

        parent::init();
    }
}