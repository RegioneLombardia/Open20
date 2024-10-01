<?php

namespace app\assets;

/**
 * Application Asset File.
 */
class IndexHeadlineAnchorAsset extends \luya\web\Asset
{
    public $sourcePath = '@app/modules/uikit/assets';

    public $css = [
        'css/indexheadlineanchor.css',
    ];

    public $js = [
    ];

    public $depends = [
        'open20\design\assets\BootstrapItaliaDesignAsset' //do not remove
    ];
}
