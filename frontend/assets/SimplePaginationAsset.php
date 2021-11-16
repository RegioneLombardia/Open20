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
 * Landing Cms Asset File.
 */
class SimplePaginationAsset extends \luya\web\Asset
{
    public $sourcePath = '@bower/jquery.simplepagination';

    public $css = [
        'simplePagination.css',
    ];

    public $js = [
        'jquery.simplePagination.js',
    ];

    public $depends = [
        //
    ];
}
