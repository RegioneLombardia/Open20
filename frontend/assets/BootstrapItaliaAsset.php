<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

namespace app\assets;

/**
 * Application Asset File.
 */
class BootstrapItaliaAsset extends \luya\web\Asset
{
    public $sourcePath = '@app/resources';

    public $css = [
      'css/bootstrap-italia.min.css',
      'css/override_bootstrap-italia.css',
    ];

    public $js = [
      //'js/bootstrap-italia.min.js',
    ];

    public $depends = [
        
    ];
}
