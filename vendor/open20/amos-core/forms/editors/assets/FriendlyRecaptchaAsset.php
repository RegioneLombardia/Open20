<?php

namespace open20\amos\core\forms\editors\assets;

class FriendlyRecaptchaAsset extends \yii\web\AssetBundle{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-core/forms/editors/assets/web/';
    public $baseUrl = '@web';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/widget.min.js',
    ];


    /**
     * @inheritdoc
     */
    public $depends = [
        '\open20\amos\core\forms\editors\assets\FriendlyRecaptchaModuleAsset'
    ];

    public $jsOptions = [
        'nomodule' => true
    ];



}