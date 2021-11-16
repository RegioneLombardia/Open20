<?php

namespace yii2fullcalendar;

use yii\web\AssetBundle;

/**
 */

class PrintAsset extends AssetBundle
{
    /**
     * [$sourcePath description]
     * @var string
     */
    public $sourcePath = '@bower/fullcalendar/dist';
    
    /**
     * [$css description]
     * @var array
     */
    public $css = [
        'fullcalendar.print.css'
    ];

    /**
     * options for the css file beeing published
     * @var [type]
     */
    public $cssOptions = [
    	'media' => 'print'
    ];
}

