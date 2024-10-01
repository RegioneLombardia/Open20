<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\assets
 * @category   CategoryName
 */

namespace open20\amos\videoconference\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * Class VideoconferenceAsset
 * @package open20\amos\videoconference\assets
 */
class VideoconferenceAsset extends AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-videoconference/src/assets/web';
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

    /**
     * @inheritdoc
     */
    public $css = [
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        //"https://jitsi01.smart.it/libs/external_api.min.js",
        //'js/videoconference.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init() {
        $jitsiDomain = Yii::$app->getModule('videoconference')->jitsiDomain ?: null;
        
        $this->js = [
            'js/videoconference.js',
            "https://".$jitsiDomain."/libs/external_api.min.js",
        ];
        parent::init();
    }

}
