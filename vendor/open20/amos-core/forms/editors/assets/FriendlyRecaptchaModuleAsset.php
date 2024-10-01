<?php

namespace open20\amos\core\forms\editors\assets;

class FriendlyRecaptchaModuleAsset extends \yii\web\AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-core/forms/editors/assets/web/';
    public $baseUrl = '@web';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/widget.module.min.js'
    ];


    /**
     * @inheritdoc
     */
    public $depends = [
    ];

    public $jsOptions = [
        'type' =>'module',
        'async' => true,
        'defer' => true
    ];



}