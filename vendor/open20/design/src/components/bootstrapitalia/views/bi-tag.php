
<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$treeDepthLabels = (isset($treeDepthLabels)) ? $treeDepthLabels : '';
$url = (isset($url)) ? $url : 'javascript:void(0)';
$label = (isset($label)) ? $label : '';
$depth = (isset($depth)) ? $depth : '0';
$icon = ($depth <= 0) ? 'tag' : 'tag-multiple';
$tooltipOption = 'data-toggle="tooltip"';

$i = 0;
$y = count($treeDepthLabels) - 1;
$titleLink = '';
if (is_array($treeDepthLabels)) {
    foreach ($treeDepthLabels as $singleDepthLabel) {
        if($i < $y) {
            $treeDepth .= $singleDepthLabel . ' ' . ' / ' . ' ';
        } else {
            $treeDepth .= $singleDepthLabel . ' ' . ' / ' . ' ' . $label;
        }
        $i++;
    }
}
$titleLink = $treeDepth;
?>


<div class="chip my-1 h-100 <?= ($depth > 0) ? 'chip-multiple' : ''?> ">
    <a href="<?= $url ?>" title="<?= $tagRoot . ($path ? " / " . $path : "") ?>" data-toggle="tooltip" class="d-flex text-decoration-none align-items-center">
        <ul class="chip-group-stacked mx-1">
            <li>
                <svg class="avatar-icon border border-white icon icon-xs">
                    <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?= $icon ?>"></use>
                </svg>
            </li>
        </ul>
        <span class="chip-label h-100 pt-1"><?= $label ?></span>
    </a>
</div>