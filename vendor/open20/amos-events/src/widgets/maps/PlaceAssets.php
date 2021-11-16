<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

namespace open20\amos\events\widgets\maps;


use yii\web\AssetBundle;

class PlaceAssets extends AssetBundle
{
    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';

    public $js = [
        'js/place.js',
    ];

    public $css = [
        'css/place.css'
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];

}