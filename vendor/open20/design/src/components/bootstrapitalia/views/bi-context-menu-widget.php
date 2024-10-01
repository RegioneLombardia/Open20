<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
$modelLabel="";
$modelId="";
$widget_title = isset($title) ? $title : Module::t('amosdesign', 'Menu contestuale');
if(!empty($model)):
    $modelId=$model->id;
    $modelLabel = strtolower($model->getGrammar()->getModelLabel());
    $idManageMenu="dropdownManageMenu". $modelLabel . $modelId;
endif;

$icon= isset($customIcon) ? $customIcon : 'dots-vertical';
?>

<?php if (!empty($buttons)) { ?>
    <div class="dropdown dropdown-manage dropleft">
        <a class="manage-menu  dropdown-toggle btn btn-xs btn-outline-tertiary btn-icon" href="javascript:void(0)" id="<?= $idManageMenu ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= $widget_title ?>">
            <svg class="icon">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#<?=$icon ?>"></use>
            </svg>
        </a>
        <div class="dropdown-menu" aria-labelledby="<?= $idManageMenu ?>">
            <div class="link-list-wrapper">
                <ul class="link-list">
                    <?php foreach ($buttons as $btn) { ?>
                        <li>
                            <a class="list-item" href="<?= $btn['url'] ?>" <?= $btn['options'] ?>>
                                <span><?= $btn['label'] ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>