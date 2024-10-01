<?php

namespace open20\amos\videoconference\assets;


use yii\web\AssetBundle;

class VideoconfrenceParticipantsAssets extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-videoconference/src/assets/web';

    /**
     * @inheritdoc
     */
    public $js = [
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'scss/participants.scss',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        //'open20\amos\layout\assets\BootstrapItaliaCustomAsset',
    ];


}