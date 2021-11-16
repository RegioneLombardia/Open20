<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\assets
 * @category   CategoryName
 */

namespace open20\amos\moodle\assets;

use yii\web\AssetBundle;

/**
 * Class MoodleAsset
 * @package open20\amos\moodle\assets
 */
class MoodleAsset extends AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-moodle/src/assets/web';
    public $publishOptions = [
        'forceCopy' => YII_DEBUG, 
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'less/moodle.less'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/moodle.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
