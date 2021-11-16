<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

/**
 * Class BootstrapItaliaAsset
 * @package open20\amos\layout\assets
 */

class BootstrapItaliaAsset extends AssetBundle {

    public $sourcePath = '@vendor/open20/amos-layout/src/assets/resources/bootstrap-italia-custom';
    public $js = [
//        'node_modules/bootstrap-italia/dist/js/bootstrap-italia.bundle.min.js',
        'node_modules/bootstrap-italia/src/js/plugins/autocomplete.js',
        'node_modules/bootstrap-italia/src/js/plugins/back-to-top.js',
        'node_modules/bootstrap-italia/src/js/plugins/carousel.js',
        'node_modules/bootstrap-italia/src/js/plugins/componente-base.js',
        'node_modules/bootstrap-italia/src/js/plugins/cookiebar.js',
        'node_modules/bootstrap-italia/src/js/plugins/custom-select.js',
        'node_modules/bootstrap-italia/src/js/plugins/dimmer.js',
        'node_modules/bootstrap-italia/src/js/plugins/dropdown.js',
        'node_modules/bootstrap-italia/src/js/plugins/fonts-loader.js',
        'node_modules/bootstrap-italia/src/js/plugins/forms.js',
        'node_modules/bootstrap-italia/src/js/plugins/forward.js',
        'node_modules/bootstrap-italia/src/js/plugins/history-back.js',
        'node_modules/bootstrap-italia/src/js/plugins/ie.js',
        'node_modules/bootstrap-italia/src/js/plugins/imgresponsive.js',
        'node_modules/bootstrap-italia/src/js/plugins/input-number.js',
        'node_modules/bootstrap-italia/src/js/plugins/list.js',
        'node_modules/bootstrap-italia/src/js/plugins/navbar.js',
        'node_modules/bootstrap-italia/src/js/plugins/navscroll.js',
        'node_modules/bootstrap-italia/src/js/plugins/notifications.js',
        'node_modules/bootstrap-italia/src/js/plugins/progress-donut.js',
        'node_modules/bootstrap-italia/src/js/plugins/rating.js',
        'node_modules/bootstrap-italia/src/js/plugins/select.js',
        'node_modules/bootstrap-italia/src/js/plugins/sticky-header.js',
        'node_modules/bootstrap-italia/src/js/plugins/sticky-wrapper.js',
        'node_modules/bootstrap-italia/src/js/plugins/timepicker.js',
        'node_modules/bootstrap-italia/src/js/plugins/track-focus.js',
        'node_modules/bootstrap-italia/src/js/plugins/transfer.js',
        'node_modules/bootstrap-italia/src/js/plugins/upload.js',

        'node_modules/bootstrap-italia/src/js/plugins/circular-loader/CircularLoader-v1.3',
        'node_modules/bootstrap-italia/src/js/plugins/datepicker/datepicker.js',
        'node_modules/bootstrap-italia/src/js/plugins/datepicker/locales/it.js',
        'node_modules/bootstrap-italia/src/js/plugins/i-sticky/i-sticky.js',
        'node_modules/bootstrap-italia/src/js/plugins/password-strength-meter/password-strength-meter.js',
        'node_modules/bootstrap-italia/src/js/plugins/polyfills/array.from.js',
    ];



    public $css = [
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

}
